<?php

namespace App\Plugins\AiNews\Services;

use App\Plugins\AiNews\Models\AiSetting;
use App\Plugins\AiNews\Models\AiLog;
use App\Models\BlogPost;
use App\Models\Admin;
use App\Core\Database;

class Crawler
{
    private $logDetails = [];
    private $fetchedCount = 0;
    private $createdCount = 0;
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function run($manual = false)
    {
        // 1. Check if Plugin is Enabled
        $enabled = AiSetting::get('plugin_enabled', '0');
        if (!$enabled && !$manual) {
            return ['status' => 'skipped', 'message' => 'Plugin is disabled'];
        }

        // 2. Check Operating Hours (Server Time)
        if (!$manual) {
            $startHour = (int) AiSetting::get('start_hour', 8);
            $endHour = (int) AiSetting::get('end_hour', 21);
            $currentHour = (int) date('H');

            if ($currentHour < $startHour || $currentHour >= $endHour) {
                return ['status' => 'skipped', 'message' => "Outside operating hours ($startHour - $endHour)"];
            }
        }

        try {
            // 3. Load Sitemaps
            $sitemaps = explode("\n", AiSetting::get('sitemap_urls', ''));
            $sitemaps = array_filter(array_map('trim', $sitemaps));

            if (empty($sitemaps)) {
                throw new \Exception("No sitemaps configured");
            }

            $maxPosts = (int) AiSetting::get('max_posts_per_cycle', 5);
            $processed = 0;

            foreach ($sitemaps as $sitemapUrl) {
                if ($processed >= $maxPosts) break;

                $this->processSitemap($sitemapUrl, $processed, $maxPosts);
            }

            AiLog::log('success', $this->fetchedCount, $this->createdCount, implode("\n", $this->logDetails));
            return ['status' => 'success', 'fetched' => $this->fetchedCount, 'created' => $this->createdCount];

        } catch (\Exception $e) {
            AiLog::log('failed', $this->fetchedCount, $this->createdCount, implode("\n", $this->logDetails), $e->getMessage());
            return ['status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    private function processSitemap($url, &$processed, $maxPosts)
    {
        $xmlContent = @file_get_contents($url);
        if (!$xmlContent) {
            $this->logDetails[] = "Failed to fetch sitemap: $url";
            return;
        }

        $xml = @simplexml_load_string($xmlContent);
        if (!$xml) {
            $this->logDetails[] = "Invalid XML in sitemap: $url";
            return;
        }

        $groq = new GroqService();
        $linker = new Linker();

        foreach ($xml->url as $urlNode) {
            if ($processed >= $maxPosts) break;

            $link = trim((string) $urlNode->loc);

            // Deduplication Check
            if ($this->isProcessed($link)) {
                continue;
            }

            $this->fetchedCount++;

            // Fetch Article HTML
            $html = @file_get_contents($link);
            if (!$html) {
                 $this->logDetails[] = "Failed to fetch HTML: $link";
                 continue;
            }

            // Extract Content
            $extracted = $this->extractContent($html);
            if (!$extracted) {
                $this->markAsProcessed($link); // Mark as processed to skip retry if extraction failed due to format
                $this->logDetails[] = "Content extraction failed (too short/invalid): $link";
                continue;
            }

            // AI Process
            $aiResult = $groq->process($extracted);

            if (!$aiResult) {
                $this->logDetails[] = "AI failed for: $link";
                continue;
            }

            // Post-Process Internal Links
            $finalContent = $linker->injectLinks($aiResult['content']);

            // Save to DB
            if ($this->savePost($aiResult, $finalContent, $extracted['image_url'])) {
                $this->markAsProcessed($link);
                $processed++;
                $this->createdCount++;
                $this->logDetails[] = "Created: " . $aiResult['title'];
            }
        }
    }

    private function isProcessed($url)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM ai_news_history WHERE source_url = :url");
        $stmt->execute(['url' => $url]);
        return $stmt->fetchColumn() > 0;
    }

    private function markAsProcessed($url)
    {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO ai_news_history (source_url) VALUES (:url)");
        $stmt->execute(['url' => $url]);
    }

    private function extractContent($html)
    {
        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        @$doc->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);

        // Title
        $titleNodes = $xpath->query('//meta[@property="og:title"]/@content');
        $title = $titleNodes->length > 0 ? $titleNodes->item(0)->nodeValue : '';
        if (!$title) {
            $t = $xpath->query('//title');
            $title = $t->length > 0 ? $t->item(0)->nodeValue : 'No Title';
        }

        // Image
        $imgNodes = $xpath->query('//meta[@property="og:image"]/@content');
        $image = $imgNodes->length > 0 ? $imgNodes->item(0)->nodeValue : '';

        // Content - Naive: grab all <p> tags
        $paragraphs = $xpath->query('//p');
        $content = '';
        foreach ($paragraphs as $p) {
            $text = trim($p->nodeValue);
            if (strlen($text) > 50) { // Skip navigation/footer noise
                $content .= "<p>$text</p>";
            }
        }

        if (strlen($content) < 200) return null; // Too short

        return [
            'title' => $title,
            'content' => $content,
            'image_url' => $image
        ];
    }

    private function savePost($aiData, $content, $imageUrl)
    {
        // Default category/author
        $catId = AiSetting::get('default_category_id', 1);
        $authorId = AiSetting::get('default_author_id', 1);

        $slug = $this->slugify($aiData['title']);

        // Ensure slug uniqueness
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
            'title' => $aiData['title'],
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $aiData['excerpt'],
            'm_title' => $aiData['meta_title'],
            'm_desc' => $aiData['meta_description'],
            'tags' => json_encode($aiData['tags'], JSON_UNESCAPED_UNICODE),
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
