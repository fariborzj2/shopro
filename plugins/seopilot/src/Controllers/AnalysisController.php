<?php

namespace SeoPilot\Enterprise\Controllers;

use App\Core\Request;
use SeoPilot\Enterprise\Analyzer\SeoPilot_Analyzer_Core;
use SeoPilot\Enterprise\Analyzer\SeoPilot_Scoring_System;
use SeoPilot\Enterprise\NLP\SeoPilot_Persian_Normalizer; // Added for autoAlt

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
            $metaTitle = $input['meta_title'] ?? '';
            $metaDesc = $input['meta_desc'] ?? '';
            // Get Slug (can be from 'slug' key)
            $slug = $input['slug'] ?? '';

            $analysis = SeoPilot_Analyzer_Core::analyze($content, $keyword, $title, $metaTitle, $metaDesc, $slug);
            $score = SeoPilot_Scoring_System::calculateScore($analysis);
            $todoList = SeoPilot_Scoring_System::generateToDoList($analysis);

            echo json_encode([
                'success' => true,
                'data' => $analysis,
                'score' => $score,
                'todo_list' => $todoList,
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
                    // Use SeoPilot_Persian_Normalizer just to ensure consistent usage if needed,
                    // though for basic concatenation it's fine.
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
}
