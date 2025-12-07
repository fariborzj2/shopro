<?php

namespace SeoPilot\Enterprise\NLP;

class SeoPilot_Persian_Normalizer
{
    /**
     * Normalize Persian text
     * - Unifies Ya/Kaf
     * - Converts numbers to English (for processing)
     * - Removes tashkeel
     */
    public static function normalize(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // Ensure UTF-8 Validity
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }

        $text = str_replace(
            ['ي', 'ك', '٤', '٥', '٦', 'ة', 'ۀ'],
            ['ی', 'ک', '۴', '۵', '۶', 'ه', 'ه'],
            $text
        );

        // Remove Tashkeel (Arabic diacritics)
        $cleaned = preg_replace('/[\x{064B}-\x{065F}]/u', '', $text);

        return trim($cleaned ?? $text);
    }

    /**
     * Convert numbers to English
     */
    public static function toEnglishNumbers(string $text): string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $text = str_replace($persian, $english, $text);
        $text = str_replace($arabic, $english, $text);

        return $text;
    }

    public static function getStopWords(): array
    {
        return [
            'از', 'به', 'با', 'برای', 'در', 'هم', 'که', 'و', 'یک', 'را',
            'این', 'آن', 'هستند', 'است', 'بود', 'شد', 'نیز', 'اما', 'یا',
            'اگر', 'تا', 'بر', 'بی', 'چون', 'چه', 'باید', 'شاید', 'می', 'نمی'
        ];
    }

    public static function removeStopWords(string $text): string
    {
        $words = explode(' ', self::normalize($text));
        $stopWords = self::getStopWords();

        $filtered = array_filter($words, function($w) use ($stopWords) {
            return !in_array($w, $stopWords) && mb_strlen($w) > 1;
        });

        return implode(' ', $filtered);
    }

    public static function wordCount(string $text): int
    {
        if (trim($text) === '') return 0;

        $text = self::normalize($text);
        $text = str_replace("\xe2\x80\x8c", '', $text); // Remove ZWNJ

        // Remove punctuation and numbers to count only words
        $cleaned = preg_replace('/[^\p{L}\s]/u', '', $text);
        $collapsed = preg_replace('/\s+/', ' ', $cleaned ?? $text);

        $words = explode(' ', trim($collapsed));
        return count(array_filter($words));
    }

    /**
     * Check for common half-space (ZWNJ) issues
     * e.g., "می شود" instead of "می‌شود", "کتاب ها" instead of "کتاب‌ها"
     */
    public static function hasHalfSpaceIssues(string $text): bool
    {
        // 1. Prefix "mi" followed by space (should be ZWNJ)
        // \bمی\s+ means "mi" followed by whitespace
        if (preg_match('/\bمی\s+/u', $text)) {
            return true;
        }

        // 2. Suffix "ha" preceded by space (often should be ZWNJ or attached)
        // heuristic: word + space + ha
        if (preg_match('/\s+ها\b/u', $text)) {
            return true;
        }

        return false;
    }
}
