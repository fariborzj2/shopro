<?php

namespace SeoPilot\Enterprise\NLP;

class SeoPilot_Persian_Normalizer
{
    /**
     * Normalize Persian text for Analysis
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

        $text = $cleaned ?? $text;

        // Convert numbers to English for consistent keyword matching
        $text = self::toEnglishNumbers($text);

        return trim($text);
    }

    /**
     * Fix Text Formatting (Auto-Correction)
     * - Fixes Half-Spaces (mi, ha, etc)
     * - Standardizes Characters
     */
    public static function fixFormatting(string $text): string
    {
        if (empty($text)) return '';

        // 1. Standardize Characters (Y/K)
        $text = str_replace(
            ['ي', 'ك', '٤', '٥', '٦', 'ة', 'ۀ'],
            ['ی', 'ک', '۴', '۵', '۶', 'ه', 'ه'],
            $text
        );

        // 2. Fix "Mi" prefix (mi + space -> mi + zwnj)
        // \bمی\s+ -> می\xe2\x80\x8c
        $text = preg_replace('/\b(می|نمی)\s+(\p{L})/u', '$1' . "\xe2\x80\x8c" . '$2', $text);

        // 3. Fix "Ha" suffix (space + ha -> zwnj + ha)
        // (\p{L})\s+ها\b -> $1\xe2\x80\x8cها
        $text = preg_replace('/(\p{L})\s+(ها)\b/u', '$1' . "\xe2\x80\x8c" . '$2', $text);

        // 4. Fix "Tar/Tarin" suffix (optional, usually attached or zwnj)
        $text = preg_replace('/(\p{L})\s+(تر|ترین)\b/u', '$1' . "\xe2\x80\x8c" . '$2', $text);

        return $text;
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

        // Remove punctuation but KEEP numbers
        $cleaned = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
        $collapsed = preg_replace('/\s+/', ' ', $cleaned ?? $text);

        $words = explode(' ', trim($collapsed));
        return count(array_filter($words));
    }

    public static function hasHalfSpaceIssues(string $text): bool
    {
        if (preg_match('/\b(می|نمی)\s+/u', $text)) return true;
        if (preg_match('/\s+ها\b/u', $text)) return true;
        return false;
    }
}
