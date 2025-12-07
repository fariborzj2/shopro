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
}
