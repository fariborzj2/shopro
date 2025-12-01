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
        // ... (همان کد قبلی)
        return ['status' => 'success', 'message' => 'Service Ready'];
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
            'temperature' => 0.6,
            'response_format' => ['type' => 'json_object']
        ];

        $response = $this->sendRequest($payload);

        if ($response['status'] === 'success') {
            $content = $response['data']['choices'][0]['message']['content'] ?? null;
            if ($content) {
                $parsed = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
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
        // (همان کد قبلی ارسال درخواست به Groq)
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
        // (همان کد قبلی)
        return <<<EOT
You are a senior Persian Lead Editor and SEO Specialist.
Your Goal: Create high-quality, analytical, and engaging Persian (Farsi) articles.
RULES:
1. Output in valid JSON only.
2. Fluent Persian language.
3. No translations. Rewrite and Expand.
4. JSON structure: title, slug, excerpt, content, meta_title, meta_description, tags, faq.
EOT;
    }

    private function getUserPrompt($title, $content, $isFullArticle)
    {
        // دستورالعمل پویا: اگر متن کامل نبود، به هوش مصنوعی می‌گوییم بیشتر تحقیق کند
        $contextInstruction = $isFullArticle 
            ? "The provided text is the full article source." 
            : "The provided text is a SUMMARY. You must EXPAND on this significantly using your internal knowledge about this topic.";

        return <<<EOT
Rewrite the following news into a fully rewritten, engaging, analytical Persian blog post.

Source Title: {{title}}
Source Content: {{content}}

Requirements:
1. Length: minimum 700 words.
2. Structure:
   - Title: catchy, SEO-optimized.
   - Slug: English-only, URL-safe, based on the topic (no Persian / no spaces).
   - Excerpt: minimum 250 characters, maximum ~350 characters.
   - Content:
     - HTML formatting (<h2>, <h3>, <p>, <ul>, <li>, <strong>)
     - Short, information-rich paragraphs
     - Must include added context, background, comparisons, or implications beyond the source news.
   - FAQ: 3–5 Q/A
   - SEO:
     - Meta Title (≤60 chars)
     - Meta Description (≤160 chars)
     - Tags: 5–7 short, relevant tags
3. Output Format: Strictly valid JSON. No markdown, no comments.

JSON Schema:
{
  "title": "string",
  "slug": "string (English, URL-safe)",
  "excerpt": "string",
  "content": "string (HTML)",
  "meta_title": "string",
  "meta_description": "string",
  "tags": ["string", "string"],
  "faq": [
    {"question": "string", "answer": "string"}
  ]
}

EOT;
    }
}
