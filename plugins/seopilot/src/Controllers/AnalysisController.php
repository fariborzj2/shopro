<?php

namespace SeoPilot\Enterprise\Controllers;

use App\Core\Database;
use App\Core\Request;
use SeoPilot\Enterprise\Analyzer\ContentAnalyzer;
use SeoPilot\Enterprise\NLP\PersianProcessor;

class AnalysisController
{
    /**
     * Handle Analysis AJAX Request
     */
    public function analyze()
    {
        // Prevent PHP errors from breaking JSON
        ini_set('display_errors', 0);
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = Request::json();

            $content = $input['content'] ?? '';
            $keyword = $input['keyword'] ?? '';
            $title = $input['title'] ?? '';

            $analysis = ContentAnalyzer::analyze($content, $keyword, $title);
            $score = $this->calculateScore($analysis, $title, $keyword);

            echo json_encode([
                'success' => true,
                'data' => $analysis,
                'score' => $score,
                'new_csrf_token' => csrf_token()
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Save SEO Meta
     */
    public function save()
    {
        // Prevent PHP errors from breaking JSON
        ini_set('display_errors', 0);
        header('Content-Type: application/json; charset=utf-8');

        try {
            $this->ensureTableExists();

            $input = Request::json();

            // Validate input
            if (!isset($input['entity_id'], $input['entity_type'], $input['meta'])) {
                throw new \Exception('Invalid input data');
            }

            $entityId = $input['entity_id'];
            $entityType = $input['entity_type'];
            $meta = $input['meta']; // Array: title, description, focus_keyword, etc.

            // --- NORMALIZATION START ---

            // 1. Normalize Focus Keyword
            $focusKeyword = $meta['focus_keyword'] ?? '';
            // Convert numbers (Persian/Arabic -> English)
            $focusKeyword = \convert_persian_numbers($focusKeyword);
            // Replace Persian comma with English comma
            $focusKeyword = str_replace('،', ',', $focusKeyword);
            // Decode entities (fix &zwnj; -> actual character)
            $focusKeyword = html_entity_decode($focusKeyword, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $focusKeyword = trim($focusKeyword);
            $meta['focus_keyword'] = $focusKeyword;

            // 2. Normalize Description & Title
            // Ensure we save the actual characters, not HTML entities (e.g., &zwnj;)
            if (isset($meta['description'])) {
                $meta['description'] = html_entity_decode($meta['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $meta['description'] = trim($meta['description']);
            }
            if (isset($meta['title'])) {
                $meta['title'] = html_entity_decode($meta['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $meta['title'] = trim($meta['title']);
            }

            // --- NORMALIZATION END ---

            $db = Database::getConnection();
            $score = $input['score'] ?? 0;

            $dataRaw = json_encode([
                'title' => $meta['title'] ?? '',
                'description' => $meta['description'] ?? '',
                'canonical' => $meta['canonical'] ?? '',
                'robots' => $meta['robots'] ?? [],
                'og_image' => $meta['og_image'] ?? '',
                'json_ld' => $meta['json_ld'] ?? []
            ], JSON_UNESCAPED_UNICODE);

            $stmt = $db->prepare("INSERT INTO seopilot_meta
                (entity_id, entity_type, focus_keyword, seo_score, data_raw, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE
                focus_keyword = VALUES(focus_keyword),
                seo_score = VALUES(seo_score),
                data_raw = VALUES(data_raw),
                updated_at = NOW()");

            $stmt->execute([
                $entityId,
                $entityType,
                $meta['focus_keyword'] ?? '',
                $score,
                $dataRaw
            ]);

            echo json_encode([
                'success' => true,
                'new_csrf_token' => csrf_token()
            ]);

        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Magic Fix: Description Generation + Auto Alt + Keyword Suggestions
     */
    public function magicFix()
    {
        ini_set('display_errors', 0);
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = Request::json();
            $content = $input['content'] ?? '';
            $title = $input['title'] ?? '';

            // 1. Description Generation (First 160 chars)
            $cleanText = strip_tags($content);
            // Decode entities (like &zwnj;) to real characters BEFORE processing
            $cleanText = html_entity_decode($cleanText, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $cleanText = preg_replace('/\s+/', ' ', $cleanText);
            $description = mb_substr($cleanText, 0, 160);
            if (mb_strlen($cleanText) > 160) {
                $lastSpace = mb_strrpos($description, ' ');
                if ($lastSpace) {
                    $description = mb_substr($description, 0, $lastSpace) . '...';
                }
            }

            // 2. Keyword Suggestion (Frequency)
            $words = explode(' ', $cleanText);
            $words = array_filter($words, function($w) { return mb_strlen($w) > 3; });
            $counts = array_count_values($words);
            arsort($counts);
            $suggestedKeyword = array_key_first($counts) ?? '';

            echo json_encode([
                'success' => true,
                'suggestion' => [
                    'description' => $description,
                    'keyword' => $suggestedKeyword
                ],
                'new_csrf_token' => csrf_token()
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Proxy for Google Suggest API
     */
    public function suggestKeywords()
    {
        ini_set('display_errors', 0);
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = Request::json();
            $query = $input['query'] ?? '';

            if (empty($query)) {
                echo json_encode(['success' => true, 'suggestions' => []]);
                exit;
            }

            $url = "https://suggestqueries.google.com/complete/search?client=chrome&q=" . urlencode($query);

            // Use cURL to fetch suggestions
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception('Curl error: ' . curl_error($ch));
            }
            curl_close($ch);

            $suggestions = [];
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data[1])) {
                    $suggestions = $data[1];
                }
            }

            echo json_encode([
                'success' => true, 
                'suggestions' => $suggestions,
                'new_csrf_token' => csrf_token()
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Auto Generate Alt Tags for Images
     */
    public function autoAlt()
    {
        ini_set('display_errors', 0);
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = Request::json();
            $content = $input['content'] ?? '';
            $title = $input['title'] ?? 'Image';

            if (empty($content)) {
                throw new \Exception('No content');
            }

            $dom = new \DOMDocument();
            // UTF-8 Hack
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            $images = $dom->getElementsByTagName('img');
            $count = 0;

            foreach ($images as $index => $img) {
                $alt = $img->getAttribute('alt');
                if (empty($alt)) {
                    // Generate Alt: Title + Index (Simple but effective fallback)
                    // "Post Title - Image 1"
                    $newAlt = $title . ' - تصویر ' . ($index + 1);
                    $img->setAttribute('alt', $newAlt);
                    $count++;
                }
            }

            if ($count > 0) {
                $newContent = $dom->saveHTML();
                // Remove XML wrapper
                $newContent = preg_replace('~<(?:!DOCTYPE|html|body)[^>]*>~i', '', $newContent);
                $newContent = preg_replace('~</(?:html|body)>~i', '', $newContent);
                $newContent = trim($newContent);

                echo json_encode([
                    'success' => true,
                    'content' => $newContent,
                    'count' => $count,
                    'new_csrf_token' => csrf_token()
                ]);
            } else {
                echo json_encode([
                    'success' => true, 
                    'count' => 0,
                    'new_csrf_token' => csrf_token()
                ]);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    private function calculateScore($analysis, $title, $keyword)
    {
        $score = 50;
        if (!empty($keyword) && strpos($title, $keyword) !== false) $score += 10;
        if (isset($analysis['keyword_stats']['density']) && $analysis['keyword_stats']['density'] >= 0.5 && $analysis['keyword_stats']['density'] <= 2.5) $score += 15;
        if (isset($analysis['word_count']) && $analysis['word_count'] > 300) $score += 10;
        if (isset($analysis['links']['internal']) && $analysis['links']['internal'] > 0) $score += 5;
        if (isset($analysis['structure']['images']) && $analysis['structure']['images'] > 0 && isset($analysis['structure']['images_no_alt']) && $analysis['structure']['images_no_alt'] == 0) $score += 5;
        return min(100, $score);
    }

    private function ensureTableExists()
    {
        try {
            $db = Database::getConnection();
            $db->exec("CREATE TABLE IF NOT EXISTS seopilot_meta (
                entity_id BIGINT UNSIGNED NOT NULL,
                entity_type VARCHAR(32) NOT NULL,
                focus_keyword VARCHAR(191),
                seo_score TINYINT UNSIGNED DEFAULT 0,
                data_raw JSON NULL,
                compiled_head MEDIUMTEXT NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (entity_type, entity_id),
                INDEX idx_score (seo_score)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        } catch (\Exception $e) {
            // Ignore error if table exists or permission issue
        }
    }
}
