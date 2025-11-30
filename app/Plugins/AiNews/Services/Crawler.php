<?php

namespace App\Plugins\AiNews\Services;

use App\Plugins\AiNews\Models\AiSetting;
use App\Plugins\AiNews\Models\AiLog;
use App\Models\BlogPost;
use App\Core\Database;

class Crawler
{
    private $logDetails = [];
    private $fetchedCount = 0;
    private $createdCount = 0;
    private $pdo;
    private $fetcher;
    private $parser;
    private $extractor;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->fetcher = new Fetcher();
        $this->parser = new FeedParser($this->fetcher);
        $this->extractor = new ContentExtractor();
    }

    public function run($manual = false)
    {
        $enabled = AiSetting::get('plugin_enabled', '0');
        if (!$enabled && !$manual) {
            return ['status' => 'skipped', 'message' => 'Plugin is disabled'];
        }

        if (!$manual) {
            $startHour = (int) AiSetting::get('start_hour', 8);
            $endHour = (int) AiSetting::get('end_hour', 21);
            $currentHour = (int) date('H');

            if ($currentHour < $startHour || $currentHour >= $endHour) {
                return ['status' => 'skipped', 'message' => "Outside operating hours ($startHour - $endHour)"];
            }
        }

        try {
            $sitemaps = explode("\n", AiSetting::get('sitemap_urls', ''));
            $sitemaps = array_filter(array_map('trim', $sitemaps));

            if (empty($sitemaps)) {
                throw new \Exception("No sitemaps configured");
            }

            $maxPosts = (int) AiSetting::get('max_posts_per_cycle', 5);
            $processed = 0;

            foreach ($sitemaps as $sitemapUrl) {
                if ($processed >= $maxPosts) break;

                $this->processSource($sitemapUrl, $processed, $maxPosts);
            }

            AiLog::log('success', $this->fetchedCount, $this->createdCount, implode("\n", $this->logDetails));
            return ['status' => 'success', 'fetched' => $this->fetchedCount, 'created' => $this->createdCount];

        } catch (\Exception $e) {
            AiLog::log('failed', $this->fetchedCount, $this->createdCount, implode("\n", $this->logDetails), $e->getMessage());
            return ['status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    private function processSource($url, &$processed, $maxPosts)
    {
        $urls = $this->parser->discoverUrls($url);

        if (empty($urls)) {
            $this->logDetails[] = "No URLs found in source: $url";
            return;
        }

        $groq = new GroqService();
        $linker = new Linker();

        foreach ($urls as $link) {
            if ($processed >= $maxPosts) break;

            if ($this->isProcessed($link)) {
                continue;
            }

            $this->fetchedCount++;

            // Fetch content with robust retry
            $fetchResult = $this->fetcher->fetch($link);
            if (!$fetchResult['success']) {
                 $this->logDetails[] = "Failed to fetch HTML: $link ({$fetchResult['error']})";
                 $this->markAsProcessed($link, 'failed', 'Fetch Error: ' . $fetchResult['error']);
                 continue;
            }

            // Extract Content
            $extracted = $this->extractor->extract($fetchResult['content']);
            if (!$extracted) {
                $this->logDetails[] = "Content extraction failed (too short/invalid): $link";
                // We mark it as failed so we don't retry forever, or maybe we skip adding to DB to retry later?
                // Requirements say "Never mark a URL as processed if extraction fails" logic was requested in prompt 1,
                // but usually persistent failure should be marked.
                // Let's NOT mark it, assuming temporary layout issue or transient failure.
                continue;
            }

            // Check Content Hash Deduplication
            $contentHash = hash('sha256', $extracted['title'] . $extracted['content']);
            if ($this->isContentDuplicate($contentHash)) {
                $this->markAsProcessed($link, 'skipped', 'Duplicate Content');
                continue;
            }

            // AI Process
            $aiResult = $groq->process($extracted);

            if (!$aiResult) {
                $this->logDetails[] = "AI failed for: $link";
                continue; // Do not mark processed, retry later
            }

            // Post-Process Internal Links
            $finalContent = $linker->injectLinks($aiResult['content'] ?? '');

            // Save to DB
            if ($this->savePost($aiResult, $finalContent, $extracted['image_url'])) {
                $this->markAsProcessed($link, 'success', null, $contentHash);
                $processed++;
                $this->createdCount++;
                $title = $aiResult['title'] ?? 'Untitled';
                $this->logDetails[] = "Created: " . $title;
            }
        }
    }

    private function isProcessed($url)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM ai_news_history WHERE source_url = :url AND status = 'success'");
        $stmt->execute(['url' => $url]);
        return $stmt->fetchColumn() > 0;
    }

    private function isContentDuplicate($hash)
    {
        // Check history for content hash
        // Note: We need to make sure 'content_hash' exists. The migration handles this.
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM ai_news_history WHERE content_hash = :hash");
            $stmt->execute(['hash' => $hash]);
            return $stmt->fetchColumn() > 0;
        } catch (\Exception $e) {
            return false; // Column might not exist yet if migration failed
        }
    }

    private function markAsProcessed($url, $status = 'success', $reason = null, $hash = null)
    {
        // Upsert logic if URL exists (e.g. previous failure)
        // For simplicity, we just insert.
        $stmt = $this->pdo->prepare("
            INSERT INTO ai_news_history (source_url, status, reason, content_hash, created_at)
            VALUES (:url, :status, :reason, :hash, NOW())
            ON DUPLICATE KEY UPDATE status = :u_status, reason = :u_reason, content_hash = :u_hash, created_at = NOW()
        ");
        $stmt->execute([
            'url' => $url,
            'status' => $status,
            'reason' => $reason,
            'hash' => $hash,
            'u_status' => $status,
            'u_reason' => $reason,
            'u_hash' => $hash
        ]);
    }

    private function savePost($aiData, $content, $imageUrl)
    {
        $catId = AiSetting::get('default_category_id', 1);
        $authorId = AiSetting::get('default_author_id', 1);

        $title = $aiData['title'] ?? 'Untitled Draft';
        $excerpt = $aiData['excerpt'] ?? '';
        $metaTitle = $aiData['meta_title'] ?? $title;
        $metaDesc = $aiData['meta_description'] ?? $excerpt;
        $tags = $aiData['tags'] ?? [];

        $slug = $this->slugify($title);

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        if ($stmt->fetchColumn() > 0) {
            $slug .= '-' . time();
        }

        $sql = "INSERT INTO blog_posts (
            category_id, author_id, title, slug, content, excerpt,
            status, meta_title, meta_description, meta_keywords,
            image_url, views_count, is_editors_pick, created_at, updated_at
        ) VALUES (
            :cat, :auth, :title, :slug, :content, :excerpt,
            'draft', :m_title, :m_desc, :tags,
            :img, 0, 0, NOW(), NOW()
        )";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'cat' => $catId,
            'auth' => $authorId,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'm_title' => $metaTitle,
            'm_desc' => $metaDesc,
            'tags' => json_encode($tags, JSON_UNESCAPED_UNICODE),
            'img' => $imageUrl
        ]);
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
