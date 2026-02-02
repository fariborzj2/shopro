<?php

namespace AiNews\Services;

use AiNews\Models\AiNewsHistory;
use AiNews\Models\AiNewsLog;
use AiNews\Models\Setting;
use AiContentPro\Services\ContentEngine;

class Processor {
    public function processPending() {
        $db = \App\Core\Database::getConnection();
        $stmt = $db->query("SELECT * FROM ai_news_history WHERE status = 'pending' ORDER BY created_at ASC LIMIT 10");
        $items = $stmt->fetchAll();

        $processed = 0;
        foreach ($items as $item) {
            try {
                $this->processItem($item);
                $processed++;
            } catch (\Exception $e) {
                AiNewsHistory::update($item['id'], ['status' => 'failed', 'reason' => $e->getMessage()]);
                AiNewsLog::error("خطا در پردازش هوش مصنوعی برای خبر: " . $item['original_url'], ['error' => $e->getMessage()]);
            }
        }
        return $processed;
    }

    private function processItem($item) {
        $html = Fetcher::fetch($item['original_url']);
        if (!$html) throw new \Exception("Could not fetch article HTML");

        $extractor = new ContentExtractor();
        $rawText = $extractor->extract($html);

        if (mb_strlen($rawText) < 500) {
            AiNewsHistory::update($item['id'], ['status' => 'skipped', 'reason' => 'Content too short']);
            return;
        }

        // Use AI Content Pro Engine
        if (!class_exists('\\AiContentPro\\Services\\ContentEngine')) {
             throw new \Exception("AI Content Pro plugin is required for processing.");
        }

        $engine = new \AiContentPro\Services\ContentEngine();
        $instruction = "Translate and rewrite the following news article into a high-quality Persian blog post. Focus on SEO and engagement.";

        $result = $engine->generateArticle($rawText, [
            'format' => 'structured',
            'instruction' => $instruction
        ]);

        $data = is_array($result) ? $result : json_decode($result, true);
        if (!$data || empty($data['title'])) {
            throw new \Exception("AI failed to return structured data");
        }

        // Save to Blog
        $category_id = Setting::get('ai_news_target_category', 1);
        $status = Setting::get('ai_news_auto_publish') === '1' ? 'published' : 'draft';

        $postData = [
            'category_id' => $category_id,
            'author_id' => 1, // System
            'title' => $data['title'],
            'slug' => $data['slug'] ?? '',
            'content' => $data['content'] ?? '',
            'excerpt' => $data['excerpt'] ?? '',
            'status' => $status,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? []
        ];

        $postId = \App\Models\BlogPost::create($postData);

        AiNewsHistory::update($item['id'], [
            'status' => 'processed',
            'post_id' => $postId,
            'content_hash' => md5($rawText)
        ]);

        AiNewsLog::info("خبر با موفقیت منتشر شد: " . $data['title'], ['post_id' => $postId]);
    }
}
