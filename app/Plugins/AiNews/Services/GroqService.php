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

    public function test()
    {
         // Simple connectivity check
        $payload = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'user', 'content' => 'Say Hello']
            ],
            'max_tokens' => 10
        ];
        return $this->sendRequest($payload);
    }

    public function process($data)
    {
        if (!$this->apiKey) return null;

        $url = $data['link'] ?? '';
        $rssSummary = strip_tags($data['content'] ?? ''); // خلاصه‌ای که خود RSS می‌دهد
        $title = $data['title'] ?? '';

        // ۱. تلاش برای استخراج متن کامل از لینک
        $fullContent = $this->fetchUrlContent($url);

        // ۲. استراتژی فال‌بک: اگر متن کامل نتوانستیم بگیریم، از خلاصه استفاده کن
        // اگر متن استخراج شده کمتر از 500 کاراکتر باشد، احتمالا مسدود شده‌ایم
        if (strlen($fullContent) < 500) {
            error_log("Warning: Could not scrape full content for $url. Using RSS summary.");
            $finalContent = $rssSummary;
            $isFullArticle = false;
        } else {
            $finalContent = $fullContent;
            $isFullArticle = true;
        }

        // اگر حتی خلاصه هم نداشتیم، رد کن
        if (strlen($finalContent) < 50) {
            error_log("Error: Content too short/empty for $url");
            return null;
        }

        // ۳. انتخاب پرامپت مناسب (اگر متن کوتاه است، باید به AI بگوییم بیشتر بسط بدهد)
        $systemPrompt = $this->getSystemPrompt();
        $userPrompt = $this->getUserPrompt($title, $finalContent, $isFullArticle);

        $payload = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt]
            ],
            'temperature' => 0.6, // کمی پایین‌تر برای دقت بیشتر
            'response_format' => ['type' => 'json_object']
        ];

        $response = $this->sendRequest($payload);

        if ($response['status'] === 'success') {
            $content = $response['data']['choices'][0]['message']['content'] ?? null;
            if ($content) {
                // 4. Sanitize and Validate Response
                $parsed = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {

                    // VALIDATION 1: No Chinese characters
                    // Range \p{Han} covers most Chinese/Kanji
                    if (preg_match('/[\p{Han}]/u', $content)) {
                        error_log("AI Error: Detected Chinese characters in response. Discarding.");
                        return null;
                    }

                    // VALIDATION 2: Enforce English Slug
                    if (isset($parsed['slug'])) {
                        // Force lowercase and remove non-alphanumeric chars (except hyphens)
                        $slug = strtolower(trim($parsed['slug']));
                        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
                        $slug = preg_replace('/-+/', '-', $slug); // Remove duplicate hyphens
                        $parsed['slug'] = trim($slug, '-');
                    }
                    // Fallback for empty slug
                    if (empty($parsed['slug'])) {
                        $parsed['slug'] = 'article-' . time();
                    }

                    // VALIDATION 3: Content Length (User: "Not short")
                    $contentLength = mb_strlen(strip_tags($parsed['content'] ?? ''));
                    if ($contentLength < 300) {
                        error_log("AI Error: Generated content is too short ($contentLength chars). Discarding.");
                        return null;
                    }

                    return $parsed;
                }
            }
        } else {
             error_log("AI API Error: " . print_r($response, true));
        }

        return null;
    }

    /**
     * تابع قدرتمند برای خواندن محتوای سایت‌ها با شبیه‌سازی مرورگر
     */
    private function fetchUrlContent($url)
    {
        if (empty($url)) return '';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // دنبال کردن ریدایرکت‌ها
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20); // حداکثر ۲۰ ثانیه تلاش
        
        // **مهم:** هدرهای مرورگر واقعی برای جلوگیری از بلاک شدن
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Referer: https://www.google.com/'
        ]);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $html = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || empty($html)) {
            return '';
        }

        // تمیز کردن HTML و استخراج متن اصلی
        // استفاده از DOMDocument برای حذف اسکریپت‌ها و استایل‌ها
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // مخفی کردن وارنینگ‌های HTML خراب
        @$dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // حذف تگ‌های مزاحم (تبلیغات، منو، اسکریپت)
        $tagsToRemove = ['script', 'style', 'nav', 'header', 'footer', 'iframe', 'noscript', 'svg'];
        foreach ($tagsToRemove as $tag) {
            $nodes = $xpath->query("//{$tag}");
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        }

        // تلاش برای پیدا کردن تگ Article یا Main که معمولا متن اصلی آنجاست
        $articleNode = $xpath->query("//article")->item(0);
        if (!$articleNode) {
            $articleNode = $xpath->query("//main")->item(0);
        }
        
        // اگر تگ اصلی پیدا شد از آن استفاده کن، وگرنه کل بادی
        $targetNode = $articleNode ? $articleNode : $dom->getElementsByTagName('body')->item(0);

        if ($targetNode) {
            return trim(preg_replace('/\s+/', ' ', $targetNode->textContent));
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

    private function getSystemPrompt()
    {
        return <<<EOT
You are a senior Persian Lead Editor and SEO Specialist.
Your Goal: Create high-quality, analytical, and engaging Persian (Farsi) articles.

CRITICAL RULES:
1. **Output Format:** Valid JSON only.
2. **Language:** Fluent, high-level Persian (Farsi) for content. English for slugs.
3. **Forbidden:** NO Chinese, Japanese, or Korean characters.
4. **Length:** Content must be comprehensive and detailed (min 1000 words).
5. **Originality:** No direct translation. Rewrite, expand, and add analysis.
EOT;
    }

    private function getUserPrompt(string $title, string $content, bool $isFullArticle): string
    {
        // ۱. تمیز کردن ورودی‌ها برای جلوگیری از به هم ریختن پرامپت
        $safeTitle = json_encode($title, JSON_UNESCAPED_UNICODE);
        $safeContent = json_encode($content, JSON_UNESCAPED_UNICODE);

        // ۲. دستورالعمل دقیق بر اساس نوع محتوا
        $expansionStrategy = $isFullArticle
            ? "The input is a full article. Your task is to REWRITE and RESTRUCTURE it to be unique, strictly avoiding plagiarism while maintaining all factual data."
            : "The input is a SUMMARY. You must act as an investigative journalist. Use your internal knowledge to EXPAND strictly on the provided topics. Add historical context, future implications, and technical details to reach the word count.";

        return <<<EOT
You are a Senior Editor-in-Chief for a leading Persian news outlet and an SEO Expert.
Your goal is to transform raw data into a high-ranking, engaging, and comprehensive article in Farsi (Persian).

---
### INPUT DATA
**Title:** {$safeTitle}
**Source Content:** {$safeContent}

**Context Mode:** {$expansionStrategy}

---
### WRITING INSTRUCTIONS
1. **Language:** Fluent, formal, and engaging Persian (Farsi). Use "Nim-fasele" (Zero-width non-joiner) correctly.
2. **Length:** The output must be LONG and DETAILED. Minimum 1200 words. Do not summarize; EXPAND.
3. **No Chinese:** Strictly forbid the use of any Chinese/Asian characters. If you encounter them in input, ignore them.
4. **Structure:**
   - **Introduction:** Hook the reader immediately.
   - **Body:** Use logical `<h2>` and `<h3>` headers.
   - **Deep Analysis:** You MUST include a dedicated section titled "تحلیل و بررسی تخصصی" (Expert Analysis) that goes beyond the news.
   - **Conclusion:** Summarize and encourage engagement.
5. **Formatting:** Return the main content as clean HTML strings (use `<p>`, `<h2>`, `<h3>`, `<ul>`, `<li>`, `<blockquote>`). Do NOT output Markdown in the JSON values.
6. **Tone:** Professional, objective, yet accessible. High readability.

---
### SEO REQUIREMENTS
1. **Slug:** STRICTLY ENGLISH. Lowercase, hyphen-separated only (e.g., `bitcoin-price-analysis`). NO Persian characters in slug.
2. **Meta Title:** Catchy, under 60 chars.
3. **Meta Description:** Click-worthy summary, under 160 chars.
4. **Tags:** 5-7 comma-separated keywords (Persian).

---
### OUTPUT FORMAT (STRICT JSON)
You must output ONLY valid JSON. Do not include markdown code blocks (like ```json).
Follow this schema strictly:

{
    "title": "H1 title in Persian",
    "slug": "english-slug-only",
    "excerpt": "A short summary (250-350 chars) for the article card.",
    "content": "Full HTML article content here...",
    "meta_title": "SEO Title",
    "meta_description": "SEO Description",
    "tags": ["Tag1", "Tag2", "Tag3"],
    "faq": [
        {"question": "Q1 in Persian?", "answer": "Short answer 1"},
        {"question": "Q2 in Persian?", "answer": "Short answer 2"},
        {"question": "Q3 in Persian?", "answer": "Short answer 3"}
    ]
}
EOT;
    }
}
