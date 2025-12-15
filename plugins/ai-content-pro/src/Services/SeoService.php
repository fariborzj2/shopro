<?php

namespace AiContentPro\Services;

use AiContentPro\Models\AiSetting;

class SeoService {
    private $gemini;

    public function __construct() {
        $this->gemini = new GeminiService();
    }

    public function generateMeta($content) {
        $maxTitle = AiSetting::get('seo_title_length', 60);
        $maxDesc = AiSetting::get('seo_desc_length', 160);

        $systemPrompt = "You are an SEO expert. " .
            "Analyze the provided Persian content and generate an optimized Meta Title and Meta Description. " .
            "Output must be a valid JSON object with keys: 'meta_title' and 'meta_description'. " .
            "Language: Persian. " .
            "Meta Title limit: {$maxTitle} chars. Meta Description limit: {$maxDesc} chars.";

        $userPrompt = "Content: " . mb_substr(strip_tags($content), 0, 1000) . "...";

        $jsonStr = $this->gemini->generateContent($userPrompt, $systemPrompt);

        $jsonStr = str_replace(['```json', '```'], '', $jsonStr);

        return json_decode($jsonStr, true);
    }
}
