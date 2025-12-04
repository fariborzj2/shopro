<?php

namespace SeoPilot\Enterprise\Analyzer;

class PixelAnalyzer
{
    // Approx pixel widths for Arial 16px (simplified map)
    // In a real env with GD, we would use imagettfbbox
    protected static $charWidths = [
        'default' => 8, // Average
        ' ' => 4,
        'i' => 4, 'l' => 4, '.' => 4, ',' => 4,
        'I' => 5, '!' => 4,
        'm' => 13, 'w' => 12, 'M' => 13, 'W' => 13,
        // Persian chars are wider on average
        'ا' => 5,
        'ی' => 9, 'ک' => 9, 'گ' => 9, 'ل' => 8,
        'م' => 10, 'ن' => 8, 'س' => 10, 'ش' => 11,
        'چ' => 9, 'ج' => 9, 'ح' => 9, 'خ' => 9,
        'ع' => 9, 'غ' => 9, 'ف' => 9, 'ق' => 9,
        'ض' => 11, 'ص' => 11, 'ث' => 9,
        'آ' => 9,
    ];

    /**
     * Calculate Pixel Width of a string (Title)
     * Target: 200px - 580px (Desktop Google Search)
     */
    public static function calculateWidth(string $text): int
    {
        // Remove HTML tags first (DOM based stripping is better, but here we assume raw text)
        $text = strip_tags($text);

        // If GD and Freetype available, use exact calculation
        if (function_exists('imagettfbbox') && file_exists(PROJECT_ROOT . '/public/fonts/arial.ttf')) {
            try {
                $box = imagettfbbox(16, 0, PROJECT_ROOT . '/public/fonts/arial.ttf', $text);
                return abs($box[4] - $box[0]);
            } catch (\Throwable $e) {
                // Fallback
            }
        }

        $width = 0;
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($chars as $char) {
            $width += self::$charWidths[$char] ?? self::$charWidths['default'];
        }

        return $width;
    }

    /**
     * Analyze text content using DOMDocument
     */
    public static function analyzeContent(string $html): array
    {
        $metrics = [
            'h1_count' => 0,
            'img_alt_missing' => 0,
            'word_count' => 0,
            'text_ratio' => 0
        ];

        if (empty($html)) return $metrics;

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        // Force UTF-8
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // H1
        $metrics['h1_count'] = $dom->getElementsByTagName('h1')->length;

        // Images
        $imgs = $dom->getElementsByTagName('img');
        foreach ($imgs as $img) {
            if (!$img->hasAttribute('alt') || empty($img->getAttribute('alt'))) {
                $metrics['img_alt_missing']++;
            }
        }

        // Text
        $body = $dom->getElementsByTagName('body')->item(0);
        $text = $body ? $body->textContent : strip_tags($html);
        $metrics['word_count'] = \SeoPilot\Enterprise\NLP\PersianProcessor::wordCount($text);

        return $metrics;
    }
}
