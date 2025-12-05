<?php

namespace SeoPilot\Enterprise\Analyzer;

use SeoPilot\Enterprise\NLP\PersianProcessor;

class ContentAnalyzer
{
    /**
     * Analyze content for SEO metrics
     */
    public static function analyze(string $content, string $keyword = '', string $title = ''): array
    {
        // 1. Clean HTML
        $text = strip_tags($content);
        $wordCount = PersianProcessor::wordCount($text);

        // 2. Keyword Analysis
        $keywordStats = ['count' => 0, 'density' => 0];
        if (!empty($keyword)) {
            $keyword = PersianProcessor::normalize($keyword);
            $normalizedContent = PersianProcessor::normalize($text);

            // Exact match count
            $keywordStats['count'] = substr_count($normalizedContent, $keyword);

            if ($wordCount > 0) {
                $keywordStats['density'] = round(($keywordStats['count'] / $wordCount) * 100, 2);
            }
        }

        // 3. Structure Analysis
        $dom = new \DOMDocument();
        // Hack to handle UTF-8 correctly in DOMDocument
        // Check for empty content to avoid loading issues
        $domContent = trim($content);
        if (empty($domContent)) {
            $domContent = '<div></div>';
        }

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $domContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $h2Count = $dom->getElementsByTagName('h2')->length;
        $h3Count = $dom->getElementsByTagName('h3')->length;
        $imgCount = $dom->getElementsByTagName('img')->length;

        $imagesWithoutAlt = 0;
        foreach ($dom->getElementsByTagName('img') as $img) {
            if (!$img->hasAttribute('alt') || empty($img->getAttribute('alt'))) {
                $imagesWithoutAlt++;
            }
        }

        // 4. Link Analysis
        $internalLinks = 0;
        $externalLinks = 0;
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost'; // Fallback for CLI/Testing

        foreach ($dom->getElementsByTagName('a') as $link) {
            $href = $link->getAttribute('href');
            if (empty($href)) continue;

            if (strpos($href, $host) !== false || strpos($href, '/') === 0) {
                $internalLinks++;
            } else {
                $externalLinks++;
            }
        }

        // 5. Readability
        // Sentence length check (Persian heuristics: looking for . ? !)
        // Use PREG_SPLIT_NO_EMPTY to avoid empty tokens
        $sentences = preg_split('/[.?!؟]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        if ($sentences === false) {
             $sentences = [];
        }

        $longSentences = 0;
        foreach ($sentences as $sentence) {
            if (PersianProcessor::wordCount($sentence) > 25) {
                $longSentences++;
            }
        }

        return [
            'word_count' => $wordCount,
            'keyword_stats' => $keywordStats,
            'structure' => [
                'h2' => $h2Count,
                'h3' => $h3Count,
                'images' => $imgCount,
                'images_no_alt' => $imagesWithoutAlt
            ],
            'links' => [
                'internal' => $internalLinks,
                'external' => $externalLinks
            ],
            'readability' => [
                'long_sentences' => $longSentences
            ]
        ];
    }
}
