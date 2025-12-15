<?php

namespace AiContentPro\Services;

use AiContentPro\Core\Config;
use AiContentPro\Core\Logger;
use App\Models\BlogPost;
use App\Core\Database;

class ContentGenerator
{
    private $gemini;

    public function __construct()
    {
        $this->gemini = new GeminiService();
    }

    public function process($payload)
    {
        if (Config::get('content_enabled') !== '1') {
            throw new \Exception("Content generation is disabled.");
        }

        $topic = $payload['topic'] ?? '';
        $urls = $payload['urls'] ?? [];

        if (empty($topic)) {
            throw new \Exception("Topic is required.");
        }

        // Check Similarity
        if ($this->checkSimilarity($topic)) {
            Logger::warning("Topic skipped due to similarity: {$topic}");
            return;
        }

        // 1. Generate Outline/Content
        $model = Config::get('content_model_gen', Config::get('content_model', 'gemini-1.5-flash'));
        $this->gemini->setModel($model); // We need to add setModel to GeminiService

        $prompt = $this->buildPrompt($topic, $urls);
        $content = $this->gemini->generate($prompt, 4000);

        // 2. Validate Language (Strict Persian)
        if (!$this->isPersian($content)) {
            throw new \Exception("Generated content is not in Persian. Aborting.");
        }

        // 3. Generate Extras
        $faq = '';
        if (Config::get('content_gen_faq', '0') === '1') {
            $faq = $this->generateFaq($topic);
        }

        // 4. Generate Meta
        $metaTitle = '';
        $metaDesc = '';
        if (Config::get('seo_enabled') === '1') {
            $seoService = new SeoService();
            $metaTitle = $seoService->generateTitle($topic);
            $metaDesc = $seoService->generateDescription($topic);
        }

        // Append FAQ to content
        if ($faq) {
            $content .= "\n\n<div class='faq-section'><h2>پرسش‌های متداول</h2>{$faq}</div>";
        }

        // 5. Save to Database
        $this->saveDraft($topic, $content, $metaTitle, $metaDesc);
    }

    private function buildPrompt($topic, $urls)
    {
        $context = "";
        if (!empty($urls)) {
            $context = "استفاده از منابع زیر الزامی است:\n" . implode("\n", $urls);
        }

        $headings = Config::get('content_allowed_headings', 'H2,H3');

        return "یک مقاله جامع و تخصصی به زبان فارسی در مورد موضوع '{$topic}' بنویس.

        {$context}

        الزامات:
        1. زبان کاملاً فارسی، رسمی و روان باشد.
        2. ساختار مقاله شامل مقدمه، بدنه اصلی با تیترهای {$headings}، و نتیجه‌گیری باشد.
        3. حداقل 1000 کلمه باشد.
        4. از تگ‌های HTML مناسب استفاده کن (p, {$headings}, ul, li).
        5. سئو فرندلی باشد و کلمات کلیدی مرتبط را به طور طبیعی استفاده کن.

        فقط متن HTML بدنه مقاله را خروجی بده. بدون تگ‌های html یا body.";
    }

    private function generateFaq($topic)
    {
        $prompt = "3 پرسش و پاسخ متداول درباره '{$topic}' بنویس.
        فرمت خروجی HTML باشد: <details><summary>پرسش</summary>پاسخ</details>.
        زبان فارسی.";
        return $this->gemini->generate($prompt, 1000);
    }

    private function checkSimilarity($topic)
    {
        // Simple exact match or levenshtein check against recent titles
        // For performance, let's check exact match first.
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM blog_posts WHERE title LIKE ?");
        $stmt->execute(["%{$topic}%"]);
        if ($stmt->fetchColumn() > 0) return true;

        return false;
    }

    private function isPersian($text)
    {
        preg_match_all('/[\x{0600}-\x{06FF}]/u', $text, $matches);
        $arabicCount = count($matches[0]);
        $totalCount = mb_strlen(strip_tags($text));

        if ($totalCount === 0) return false;
        return ($arabicCount / $totalCount) > 0.6;
    }

    private function saveDraft($title, $content, $metaTitle, $metaDesc)
    {
        $db = Database::getConnection();
        $slug = $this->slugify($title);
        $status = Config::get('content_status', 'draft');

        $stmt = $db->query("SELECT id FROM admins ORDER BY id ASC LIMIT 1");
        $authorId = $stmt->fetchColumn() ?: 1;

        $stmt = $db->prepare("INSERT INTO blog_posts (
            title, slug, content, excerpt, author_id, status,
            meta_title, meta_description, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

        $excerpt = mb_substr(strip_tags($content), 0, 200) . '...';

        $stmt->execute([
            $title,
            $slug,
            $content,
            $excerpt,
            $authorId,
            $status,
            $metaTitle,
            $metaDesc
        ]);

        Logger::info("Content Generated: {$title}");
    }

    private function slugify($text)
    {
        $text = trim($text);
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return $text;
    }
}
