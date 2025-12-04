<?php

namespace SeoPilot\Enterprise\NLP;

class PersianProcessor
{
    /**
     * Normalize Persian text
     * - Unifies Ya/Kaf
     * - Converts numbers to English (for URL safety) or Persian (for display)
     * - Removes tashkeel
     */
    public static function normalize(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        $text = str_replace(
            ['ي', 'ك', '٤', '٥', '٦', 'ة', 'ۀ'],
            ['ی', 'ک', '۴', '۵', '۶', 'ه', 'ه'],
            $text
        );

        // Remove Tashkeel (Arabic diacritics)
        $text = preg_replace('/[\x{064B}-\x{065F}]/u', '', $text);

        return trim($text);
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

    /**
     * Smart Word Count utilizing ZWNJ (Zero Width Non-Joiner)
     * Correctly counts "می‌شود" as one word.
     */
    public static function wordCount(string $text): int
    {
        $text = self::normalize($text);

        // Remove ZWNJ for counting purposes (merge parts) or treat as letter?
        // Actually, "می‌شود" is one word. If we replace ZWNJ with nothing, it becomes "می‌شود".
        // If we replace with space, it becomes two.
        // Standard Persian logic: ZWNJ connects parts of ONE word.
        // So we remove ZWNJ.
        $text = str_replace("\xe2\x80\x8c", '', $text); // Remove ZWNJ

        // Remove punctuation
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);

        // Collapse multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);

        $words = explode(' ', trim($text));
        return count(array_filter($words));
    }

    /**
     * Simple Stemmer for Keyword Matching
     * (Basic implementation - full stemming requires heavy dictionary)
     */
    public static function stem(string $word): string
    {
        $word = self::normalize($word);

        // Remove common suffixes
        $suffixes = ['ها', 'ان', 'تر', 'ترین', 'ی', 'ای'];

        foreach ($suffixes as $suffix) {
            if (mb_substr($word, -mb_strlen($suffix)) === $suffix) {
                // Heuristic: Don't stem if result is too short
                $stem = mb_substr($word, 0, -mb_strlen($suffix));
                if (mb_strlen($stem) > 2) {
                    return $stem;
                }
            }
        }

        return $word;
    }
}
