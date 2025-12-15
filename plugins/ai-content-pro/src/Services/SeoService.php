<?php

namespace AiContentPro\Services;

use AiContentPro\Core\Config;

class SeoService
{
    private $gemini;

    public function __construct()
    {
        $this->gemini = new GeminiService();
    }

    public function generateTitle($topic)
    {
        $limit = Config::get('seo_meta_title_len', 60);
        $prompt = "یک عنوان سئو (Meta Title) جذاب و بهینه برای مقاله‌ای با موضوع '{$topic}' بنویس.
        حداکثر {$limit} کاراکتر باشد. فقط متن عنوان را برگردان. زبان فارسی.";

        return trim($this->gemini->generate($prompt, 100));
    }

    public function generateDescription($topic)
    {
        $limit = Config::get('seo_meta_desc_len', 160);
        $prompt = "یک توضیحات متا (Meta Description) جذاب و بهینه برای مقاله‌ای با موضوع '{$topic}' بنویس.
        حداکثر {$limit} کاراکتر باشد. شامل کلمات کلیدی اصلی باشد. فقط متن را برگردان. زبان فارسی.";

        return trim($this->gemini->generate($prompt, 300));
    }

    public function generateSchema($topic, $title, $description, $url)
    {
        if (Config::get('seo_schema_enabled', '0') !== '1') {
            return '';
        }

        $prompt = "یک اسکیما JSON-LD از نوع Article برای مقاله‌ای با موضوع '{$topic}'، عنوان '{$title}' و توضیحات '{$description}' بساز.
        URL: {$url}
        فقط کد JSON را برگردان.";

        $json = $this->gemini->generate($prompt, 1000);
        return str_replace(['```json', '```'], '', $json);
    }

    public function checkKeywordDensity($content, $keyword)
    {
        $totalWords = str_word_count(strip_tags($content));
        if ($totalWords == 0) return 0;

        $keywordCount = substr_count(strip_tags($content), $keyword);
        return ($keywordCount / $totalWords) * 100;
    }
}
