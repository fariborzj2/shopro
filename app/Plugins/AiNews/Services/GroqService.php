<?php

namespace App\Plugins\AiNews\Services;

use App\Plugins\AiNews\Models\AiSetting;
use DOMDocument;
use DOMXPath;

class GroqService
{
    private $apiKey;
    private $endpoint;
    private $model;

    public function __construct()
    {
        $this->apiKey = AiSetting::get('groq_api_key');
        $this->endpoint = AiSetting::get('groq_endpoint', 'https://api.groq.com/openai/v1/chat/completions');
        $this->model = AiSetting::get('groq_model', 'llama-3.3-70b-versatile');
    }

    public function process($data)
    {
        if (!$this->apiKey) return ['error' => 'API Key missing'];

        $url = $data['link'] ?? '';
        $title = $data['title'] ?? '';
        $initialContent = strip_tags($data['content'] ?? '');

        // استراتژی: اگر محتوای اولیه کوتاه بود (زیر ۵۰۰ کاراکتر)، خودمان دوباره اسکرپ می‌کنیم
        if (mb_strlen($initialContent) < 500 && !empty($url)) {
            $scrapedContent = $this->fetchUrlContent($url);
            // اگر اسکرپ موفق بود جایگزین کن، وگرنه همان قبلی را نگه دار
            if (mb_strlen($scrapedContent) > 500) {
                $finalContent = $scrapedContent;
                $isFullArticle = true;
            } else {
                $finalContent = $initialContent; // چاره‌ای نیست، با همین بساز
                $isFullArticle = false;
            }
        } else {
            $finalContent = $initialContent;
            $isFullArticle = true;
        }

        // اگر کلا محتوایی نداریم، بیخیال شو
        if (mb_strlen($finalContent) < 100) {
            return ['error' => 'Content too short/empty even after scraping'];
        }

        $systemPrompt = $this->getSystemPrompt();
        $userPrompt = $this->getUserPrompt($title, $finalContent, $isFullArticle);

        $payload = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt]
            ],
            'temperature' => 0.6,
            'response_format' => ['type' => 'json_object']
        ];

        $maxRetries = 3;
        $attempt = 0;
        $response = null;

        while ($attempt < $maxRetries) {
            $response = $this->sendRequest($payload);

            if ($response['status'] === 'success' && isset($response['data']['error'])) {
                $errorMsg = $response['data']['error']['message'] ?? '';
                // Check for rate limit error
                if (stripos($errorMsg, 'rate limit') !== false) {
                    $attempt++;
                    $waitTime = 10; // Default wait time

                    // Try to parse wait time from error message "Please try again in X s"
                    if (preg_match('/try again in (\d+(\.\d+)?)s/', $errorMsg, $matches)) {
                        $waitTime = ceil((float)$matches[1]);
                    }

                    if ($attempt < $maxRetries) {
                        sleep($waitTime + 1); // Add slight buffer
                        continue;
                    }
                }
                return ['error' => 'API Error: ' . $errorMsg];
            }
            break;
        }

        if ($response && $response['status'] === 'success') {
            $rawContent = $response['data']['choices'][0]['message']['content'] ?? null;
            
            if ($rawContent) {
                // پاکسازی مارک‌داون‌های احتمالی که مدل اضافه می‌کند
                $cleanJson = preg_replace('/^```json\s*/i', '', $rawContent);
                $cleanJson = preg_replace('/^```\s*/i', '', $cleanJson);
                $cleanJson = preg_replace('/\s*```$/', '', $cleanJson);

                $parsed = json_decode($cleanJson, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    // Remove Chinese characters instead of failing
                    $parsed = $this->removeChineseCharsRecursive($parsed);
                    return $this->sanitizeOutput($parsed);
                } else {
                    return ['error' => 'JSON Decode Error: ' . json_last_error_msg()];
                }
            }
        }

        return ['error' => 'Network Error: ' . ($response['message'] ?? 'Unknown')];
    }

    private function fetchUrlContent($url)
    {
        if (empty($url)) return '';

        // لیست User-Agent برای چرخش
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_ENCODING, ''); // **مهم: هندل کردن Gzip**
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: ' . $userAgents[array_rand($userAgents)],
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1'
        ]);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$html || $httpCode >= 400) return '';

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        // تبدیل انکدینگ برای جلوگیری از بهم ریختگی حروف
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // حذف تگ‌های مزاحم
        $tagsToRemove = ['script', 'style', 'nav', 'header', 'footer', 'iframe', 'noscript', 'svg', 'form', 'aside', 'ads'];
        foreach ($tagsToRemove as $tag) {
            foreach ($xpath->query("//{$tag}") as $node) {
                $node->parentNode->removeChild($node);
            }
        }

        // اولویت‌بندی برای یافتن متن اصلی
        $queries = ["//article", "//main", "//div[contains(@class, 'content')]", "//body"];
        foreach ($queries as $query) {
            $node = $xpath->query($query)->item(0);
            if ($node) {
                return trim(preg_replace('/\s+/', ' ', $node->textContent));
            }
        }

        return '';
    }

    private function sendRequest($payload)
    {
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) return ['status' => 'error', 'message' => $error];
        return ['status' => 'success', 'data' => json_decode($response, true)];
    }

    private function sanitizeOutput($parsed)
    {
        // اطمینان از اینکه اسلاگ انگلیسی و تمیز است
        if (isset($parsed['slug'])) {
            $slug = strtolower(trim($parsed['slug']));
            $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
            $parsed['slug'] = trim(preg_replace('/-+/', '-', $slug), '-');
        } else {
            $parsed['slug'] = '';
        }
        return $parsed;
    }

    private function removeChineseCharsRecursive($data) {
        if (is_string($data)) {
            // Remove Han (Chinese) characters
            return preg_replace('/[\p{Han}]/u', '', $data);
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->removeChineseCharsRecursive($value);
            }
        }
        return $data;
    }

private function getSystemPrompt()
{
    return <<<EOT
You are a highly experienced **Senior Persian Lead Editor and Tech Journalist**.
Your goal is to transform English source material into high-authority, original, and deeply analytical Persian articles.

**CORE DIRECTIVES:**
1.  **NO TRANSLATION:** Do not translate sentence-by-sentence. Read the source, understand the core concepts, and write a completely new article in fluent, modern Persian (Farsi).
2.  **AUDIENCE:** Write for a professional audience. The tone should be formal, authoritative, yet engaging (رسمی، تحلیلی و روان).
3.  **DEPTH:** Do not just summarize. Expand on points using logic, comparisons, pros/cons, and future implications.
4.  **FORMATTING:**
    - Use standard Persian punctuation (including correct spacing).
    - Use <strong> sparingly for impact.
    - Structure content logically using <h2> for main sections and <h3> for subsections.
    - Bold all **main SEO keywords** throughout the article for emphasis and clarity.
    - Never put the main title inside the 'content' field (start with the introduction).
5.  **RESTRICTIONS:**
    - Output MUST be a single, valid JSON object.
    - No conversational filler ("Here is the JSON...").
    - Use English only for specific technical terms (e.g., PHP, React, SEO) where common in the industry.

EOT;
}


private function getUserPrompt($title, $content, $isFullArticle)
{
    // Safety check: Cut at the nearest sentence to avoid broken context
    if (mb_strlen($content) > 20000) {
        $truncated = mb_substr($content, 0, 20000);
        $lastPeriod = mb_strrpos($truncated, '.');
        $content = ($lastPeriod !== false) ? mb_substr($truncated, 0, $lastPeriod + 1) : $truncated;
    }

    $strategy = $isFullArticle
        ? "Analyze this article deeply. Reconstruct it as a comprehensive 'Definitive Guide' or 'In-depth Analysis' in Persian. Add value beyond the original text."
        : "Use this summary as a seed. Expand it into a full-blown investigative article. Fill in gaps with expert knowledge/reasoning.";

    // Encoding input to prevent prompt injection or JSON syntax errors in the prompt itself
    $safeInput = json_encode([
        'title' => $title,
        'content' => $content
    ], JSON_UNESCAPED_UNICODE);

    return <<<EOT
**TASK:** Create a premium Persian article based on the provided input.

**STRATEGY:** {$strategy}

**INPUT DATA:**
{$safeInput}

**CONTENT REQUIREMENTS:**
1.  **Length:** Aim for comprehensive coverage (approx. 1500+ words equivalent depth).
2.  **Structure:**
    - **Excerpt:** A captivating lead paragraph (Check-mate intro) that hooks the reader instantly.
    - **Body:** Properly nested <h2> and <h3> tags. Use bullet points <ul> for readability.
    - **Keyword Rules:** All primary SEO keywords must be **bolded** consistently.
    - **Conclusion:** A section titled "جمع‌بندی و نتیجه‌گیری" that synthesizes the insights.
3.  **SEO:**
    - **Slug:** English, lowercase, hyphenated, optimized for keywords.
    - **Tags:** 5 highly relevant Persian tags (singular/plural correct).
    - **FAQ:** 3 distinct questions that address user intent, not just random facts.

**OUTPUT JSON SCHEMA (Strict):**
You must output ONLY this JSON structure:
{
    "title": "string (Persian, no HTML, engaging header, max 60 chars)",
    "slug": "string (english-slug-format)",
    "excerpt": "string (HTML allowed, min 100 words, engaging intro)",
    "content": "string (HTML body string. Start directly with the first paragraph or H2. DO NOT include H1)",
    "meta_title": "string (SEO optimized, max 60 chars)",
    "meta_description": "string (SEO optimized, max 160 chars)",
    "tags": ["string", "string", "string", "string", "string"],
    "faq": [
        {"question": "string", "answer": "string"},
        {"question": "string", "answer": "string"},
        {"question": "string", "answer": "string"}
    ]
}
EOT;
}
    
}
