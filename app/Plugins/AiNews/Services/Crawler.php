<?php

namespace App\Plugins\AiNews\Services;

use App\Plugins\AiNews\Models\AiSetting;
use App\Plugins\AiNews\Models\AiLog;
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
        // افزایش محدودیت زمانی و حافظه برای پردازش‌های سنگین
        set_time_limit(600); 
        ini_set('memory_limit', '512M');

        $enabled = AiSetting::get('plugin_enabled', '0');
        if (!$enabled && !$manual) {
            return ['status' => 'skipped', 'message' => 'Plugin is disabled'];
        }

        if (!$manual) {
            // منطق بررسی ساعت و بازه زمانی
            $startHour = (int) AiSetting::get('start_hour', 8);
            $endHour = (int) AiSetting::get('end_hour', 23);
            $currentHour = (int) date('H');

            if ($currentHour < $startHour || $currentHour >= $endHour) {
                return ['status' => 'skipped', 'message' => "Outside hours ($startHour - $endHour)"];
            }

            $interval = (int) AiSetting::get('execution_interval', 1);
            if (!$this->shouldRun($interval)) {
                 return ['status' => 'skipped', 'message' => "Interval wait time"];
            }
        }

        try {
            $sitemaps = explode("\n", AiSetting::get('sitemap_urls', ''));
            $sitemaps = array_filter(array_map('trim', $sitemaps));

            if (empty($sitemaps)) throw new \Exception("No sitemaps configured");

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
        $urls = $this->parser->discoverUrls($url); // فرض بر این است که این متد آرایه‌ای از لینک‌ها می‌دهد
        if (empty($urls)) {
            $this->logDetails[] = "No URLs found in: $url";
            return;
        }

        $groq = new GroqService();
        $linker = new Linker(); // فرض بر وجود این کلاس

        foreach ($urls as $link) {
            if ($processed >= $maxPosts) break;

            if ($this->isProcessed($link)) continue;

            $this->fetchedCount++;

            // دریافت محتوا
            $fetchResult = $this->fetcher->fetch($link);
            if (!$fetchResult['success']) {
                 $this->markAsProcessed($link, 'failed', 'Fetch Error');
                 continue;
            }

            $extracted = $this->extractor->extract($fetchResult['content']);
            if (!$extracted || mb_strlen($extracted['content'] ?? '') < 50) {
                $this->logDetails[] = "Extraction failed/empty: $link";
                continue;
            }

            // چک لیست سیاه (Anti-Spam)
            $blacklist = ['casino', 'porn', 'xxx', 'gambling'];
            foreach ($blacklist as $badWord) {
                if (stripos($extracted['title'] . $link, $badWord) !== false) {
                    $this->markAsProcessed($link, 'skipped', 'Blacklisted');
                    continue 2;
                }
            }

            // بررسی تکراری بودن محتوا (Content Hash)
            $contentHash = hash('sha256', $extracted['title']);
            if ($this->isContentDuplicate($contentHash)) {
                $this->markAsProcessed($link, 'skipped', 'Duplicate Content');
                continue;
            }

            // ارسال به هوش مصنوعی
            // نکته: GroqService خودش چک می‌کند اگر متن کم بود دوباره اسکرپ کند
            $extracted['link'] = $link; 
            $aiResult = $groq->process($extracted);

            if (isset($aiResult['error'])) {
                $this->logDetails[] = "AI Error: {$aiResult['error']} | Link: $link";
                // اینجا مارک نمی‌کنیم تا در دور بعدی شاید تلاش مجدد شود
                continue;
            }

            // لینک‌سازی داخلی
            $finalContent = $linker->injectLinks($aiResult['content']);

            // ذخیره در دیتابیس
            if ($this->savePost($aiResult, $finalContent, $extracted['image_url'])) {
                $this->markAsProcessed($link, 'success', null, $contentHash);
                $processed++;
                $this->createdCount++;
                $this->logDetails[] = "Created: " . ($aiResult['title'] ?? 'Unknown');
            }
        }
    }

    private function savePost($aiData, $content, $imageUrl)
    {
        $catId = AiSetting::get('default_category_id', 1);
        $authorId = AiSetting::get('default_author_id', 1);
        $title = $aiData['title'] ?? 'Untitled Draft';

        // 1. اولویت با اسلاگ انگلیسی AI است
        $slug = !empty($aiData['slug']) ? $aiData['slug'] : $this->slugify($title);
        
        // یونیک کردن اسلاگ
        $originalSlug = $slug;
        $counter = 1;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $excerpt = $aiData['excerpt'] ?? '';
        // اگر تصویر معتبر نبود، نال رد کن
        $finalImageUrl = filter_var($imageUrl, FILTER_VALIDATE_URL) ? $imageUrl : null;
        $faq = $aiData['faq'] ?? [];

        try {
            $this->pdo->beginTransaction();

            // Insert Post
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
            $stmt->execute([
                'cat' => $catId,
                'auth' => $authorId,
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'excerpt' => $excerpt,
                'm_title' => $aiData['meta_title'] ?? $title,
                'm_desc' => $aiData['meta_description'] ?? $excerpt,
                'tags' => json_encode($aiData['tags'] ?? [], JSON_UNESCAPED_UNICODE),
                'img' => $finalImageUrl
            ]);

            $postId = $this->pdo->lastInsertId();

            // Insert FAQ (Raw SQL for transaction safety)
            if (!empty($faq) && is_array($faq)) {
                $stmtFaq = $this->pdo->prepare("INSERT INTO faq_items (question, answer, type, status, position, created_at) VALUES (?, ?, 'blog_faq', 'active', 0, NOW())");
                $stmtPivot = $this->pdo->prepare("INSERT INTO blog_post_faq (post_id, faq_id) VALUES (?, ?)");

                foreach ($faq as $item) {
                    if (empty($item['question']) || empty($item['answer'])) continue;
                    
                    $stmtFaq->execute([$item['question'], $item['answer']]);
                    $faqId = $this->pdo->lastInsertId();
                    
                    $stmtPivot->execute([$postId, $faqId]);
                }
            }

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            $this->logDetails[] = "DB Transaction Failed: " . $e->getMessage();
            return false;
        }
    }

    // --- Helper Methods ---

    private function shouldRun($intervalHours) {
        $stmt = $this->pdo->prepare("SELECT created_at FROM ai_news_logs WHERE status = 'success' ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $lastRun = $stmt->fetchColumn();
        if (!$lastRun) return true;
        return time() >= (strtotime($lastRun) + ($intervalHours * 3600));
    }

    private function isProcessed($url) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM ai_news_history WHERE source_url = :url AND status = 'success'");
        $stmt->execute(['url' => $url]);
        return $stmt->fetchColumn() > 0;
    }

    private function isContentDuplicate($hash) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM ai_news_history WHERE content_hash = :hash");
        $stmt->execute(['hash' => $hash]);
        return $stmt->fetchColumn() > 0;
    }

    private function markAsProcessed($url, $status, $reason = null, $hash = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO ai_news_history (source_url, status, reason, content_hash, created_at)
            VALUES (:url, :status, :reason, :hash, NOW())
            ON DUPLICATE KEY UPDATE status = :u_status, reason = :u_reason, created_at = NOW()
        ");
        $stmt->execute(['url' => $url, 'status' => $status, 'reason' => $reason, 'hash' => $hash, 'u_status' => $status, 'u_reason' => $reason]);
    }

    private function slugExists($slug) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetchColumn() > 0;
    }

    private function slugify($text) {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $text);
        return preg_replace('/[\s-]+/', '-', $text);
    }
}
