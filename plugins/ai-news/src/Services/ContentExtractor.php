<?php

namespace AiNews\Services;

class ContentExtractor {
    public function extract($html) {
        if (empty($html)) return null;

        // Basic clean up
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $html);
        $html = preg_replace('/<nav\b[^>]*>(.*?)<\/nav>/is', "", $html);
        $html = preg_replace('/<footer\b[^>]*>(.*?)<\/footer>/is', "", $html);
        $html = preg_replace('/<header\b[^>]*>(.*?)<\/header>/is', "", $html);

        // Try to find article content
        // Heuristic: target <article>, or main containers
        if (preg_match('/<article[^>]*>(.*?)<\/article>/is', $html, $matches)) {
            $content = $matches[1];
        } else {
            // Fallback: strip tags and take text (not ideal but better than nothing)
            $content = $html;
        }

        $text = strip_tags($content, '<p><h1><h2><h3><h4><ul><li><strong><b>');
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }
}
