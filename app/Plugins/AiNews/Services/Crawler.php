<?php

namespace App\Plugins\AiNews\Services;

use App\Plugins\AiNews\Models\AiSetting;
use App\Plugins\AiNews\Models\AiLog;
use App\Models\BlogPost;
use App\Models\Admin;
use App\Core\Database;
use DOMDocument;
use DOMXPath;

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

                // Recursively collect up to 2x maxPosts to filter
                $this->processSitemap($sitemapUrl, $processed, $maxPosts);
            }

            AiLog::log('success', $this->fetchedCount, $this->createdCount, implode("\n", $this->logDetails));
            return ['status' => 'success', 'fetched' => $this->fetchedCount, 'created' => $this->createdCount];

        } catch (\Exception $e) {
            AiLog::log('failed', $this->fetchedCount, $this->createdCount, implode("\n", $this->logDetails), $e->getMessage());
            return ['status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    /**
     * Main entry point for processing a sitemap URL.
     * Manages the loop of fetching content and calling AI.
     */
    private function processSitemap($url, &$processed, $maxPosts)
    {
        // Collect URLs (handling nested sitemaps recursively)
        $urls = $this->collectUrls($url);

        if (empty($urls)) {
            $this->logDetails[] = "No URLs found in source: $url";
            return;
        }

        $groq = new GroqService();
        $linker = new Linker();

        foreach ($urls as $link) {
            if ($processed >= $maxPosts) break;

            $link = trim($link);

            // Deduplication Check
            if ($this->isProcessed($link)) {
                continue;
            }

            $this->fetchedCount++;

            // Fetch Article HTML
            $html = $this->fetchUrl($link);
            if (!$html) {
                 $this->logDetails[] = "Failed to fetch HTML: $link";
                 continue;
            }

            // Extract Content
            $extracted = $this->extractContent($html);
            if (!$extracted) {
                // Do NOT mark as processed if extraction fails (per requirement), so it retries later.
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
            $finalContent = $linker->injectLinks($aiResult['content'] ?? '');

            // Save to DB
            if ($this->savePost($aiResult, $finalContent, $extracted['image_url'])) {
                $this->markAsProcessed($link);
                $processed++;
                $this->createdCount++;
                $title = $aiResult['title'] ?? 'Untitled';
                $this->logDetails[] = "Created: " . $title;
            }
        }
    }

    /**
     * Universal Feed Parser & Collector
     * Recursively collects article URLs from XML Sitemaps, Sitemap Indices, RSS, and Atom.
     */
    private function collectUrls($url, $depth = 0)
    {
        if ($depth > 2) return []; // Prevent deep recursion

        $content = $this->fetchUrl($url);
        if (!$content) return [];

        // Attempt to parse as XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        $urls = [];

        if ($xml === false || count($errors) > 0) {
            // XML Parsing Failed -> Try Regex Fallback if content looks like HTML/Text
            // Requirement: "fallback URL detection via regex if XML parsing fails"
            // We search for patterns that look like <loc>...</loc> or <link>...</link> just in case it's strict XML issues.
            preg_match_all('/<loc>(.*?)<\/loc>/', $content, $matches);
            if (!empty($matches[1])) {
                $urls = array_merge($urls, $matches[1]);
            } else {
                preg_match_all('/<link>(.*?)<\/link>/', $content, $matches);
                if (!empty($matches[1])) {
                    $urls = array_merge($urls, $matches[1]);
                }
            }
            return array_unique($urls);
        }

        // Detect Feed Type via Root Element or Children
        $root = $xml->getName();

        // 1. Sitemap Index (<sitemapindex>)
        if ($root === 'sitemapindex') {
            if (isset($xml->sitemap)) {
                foreach ($xml->sitemap as $sitemap) {
                    $loc = (string)$sitemap->loc;
                    if ($loc) {
                        // Recursively fetch nested sitemaps
                        $nestedUrls = $this->collectUrls($loc, $depth + 1);
                        $urls = array_merge($urls, $nestedUrls);
                    }
                }
            }
        }
        // 2. Standard Sitemap (<urlset>)
        elseif ($root === 'urlset') {
            if (isset($xml->url)) {
                foreach ($xml->url as $urlNode) {
                    // Support Google News extensions (<news:news>) - typically the URL is still in <loc>
                    // But <loc> is standard.
                    if (isset($urlNode->loc)) {
                        $urls[] = (string)$urlNode->loc;
                    }
                }
            }
        }
        // 3. RSS Feed (<rss> or <rdf:RDF>)
        elseif ($root === 'rss' || strpos($root, 'RDF') !== false) {
            if (isset($xml->channel->item)) {
                foreach ($xml->channel->item as $item) {
                    if (isset($item->link)) {
                        $urls[] = (string)$item->link;
                    }
                }
            }
        }
        // 4. Atom Feed (<feed>)
        elseif ($root === 'feed') {
            if (isset($xml->entry)) {
                foreach ($xml->entry as $entry) {
                    // Atom links are attributes: <link href="..." />
                    if (isset($entry->link)) {
                        // Handle multiple links (rel="alternate", rel="self", etc.)
                        foreach ($entry->link as $linkNode) {
                            $attributes = $linkNode->attributes();
                            $href = (string)$attributes['href'];
                            $rel = isset($attributes['rel']) ? (string)$attributes['rel'] : 'alternate';

                            // Prefer alternate (content) links, skip 'self' or 'replies'
                            if ($rel === 'alternate' || empty($rel)) {
                                $urls[] = $href;
                                break; // Take the first valid alternate link per entry
                            }
                        }
                    }
                }
            }
        }

        return array_unique($urls);
    }

    private function fetchUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // Robust SSL settings
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // Robust Browser Headers
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
            'Cache-Control: no-cache',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($content === false) {
             $this->logDetails[] = "Curl Error fetching $url: $error";
             return null;
        }

        if ($httpCode >= 400) {
             $this->logDetails[] = "HTTP Error $httpCode fetching $url";
             return null;
        }

        return $content;
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
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        // Force UTF-8 encoding
        @$doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        $xpath = new DOMXPath($doc);

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

        // Content Extraction
        // Look for common article containers to avoid extracting navigation/footer
        $content = '';
        $containers = $xpath->query('//article | //div[contains(@class, "post-content")] | //div[contains(@class, "entry-content")] | //div[contains(@class, "article-body")]');

        if ($containers->length > 0) {
            // Use the first best container
            $container = $containers->item(0);
            $paragraphs = $xpath->query('.//p', $container);
        } else {
            // Fallback to all paragraphs
            $paragraphs = $xpath->query('//p');
        }

        foreach ($paragraphs as $p) {
            $text = trim($p->nodeValue);
            // Filter noise
            if (strlen($text) > 50) {
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
