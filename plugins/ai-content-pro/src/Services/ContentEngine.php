<?php

namespace AiContentPro\Services;

use AiContentPro\Models\AiSetting;
use AiContentPro\Models\AiLog;

class ContentEngine {
    private $gemini;

    public function __construct() {
        $this->gemini = new GeminiService();
    }

    public function generateArticle($topic, $options = []) {
        $language = 'Persian (Farsi)';

        // Handle Source Types
        $sourceContext = "";
        if (!empty($options['source_url'])) {
            $sourceContent = $this->fetchUrlContent($options['source_url']);
            if ($sourceContent) {
                $sourceContext = "BASE CONTENT: \n" . mb_substr($sourceContent, 0, 8000) . "\n\nINSTRUCTION: Rewrite and expand the above content in {$language}.";
            }
        } elseif (!empty($options['source_list'])) {
             $sourceContext = "SOURCE FACTS: \n" . $options['source_list'] . "\n\nINSTRUCTION: Create an article based on these facts.";
        }

        $systemPrompt = "You are an expert SEO content writer fluent in {$language}. " .
            "Your task is to write a comprehensive, engaging, and SEO-optimized article. " .
            "Strictly adhere to the following rules:\n" .
            "1. Output Language: {$language} ONLY.\n" .
            "2. Structure: Use H2 and H3 tags for headings. Do not use H1.\n" .
            "3. Format: Return pure HTML content (paragraphs, lists, headings) without ```html``` code blocks or markdown.\n" .
            "4. Tone: Professional yet accessible.\n" .
            "5. Length: Comprehensive (approx 1000-1500 words).\n" .
            "6. Content: informative, valuable, and original.";

        if (!empty($options['keywords'])) {
            $systemPrompt .= "\n7. Focus Keywords: " . implode(', ', $options['keywords']);
        }

        if (AiSetting::get('enable_faq_gen') === '1') {
             $systemPrompt .= "\n8. Add a FAQ section at the end with 3-5 questions and answers.";
        }

        $userPrompt = "Write an article about: " . $topic;

        if ($sourceContext) {
            $userPrompt .= "\n\n" . $sourceContext;
        }

        $content = $this->gemini->generateContent($userPrompt, $systemPrompt);

        // Internal Links Injection (Simple placeholder logic)
        if (AiSetting::get('enable_internal_links') === '1' && !empty($content)) {
            // In a real scenario, we would query DB for related posts.
            // Here we just mock the logic or leave it to the post-processing.
            // For now, let's ask Gemini to suggest internal links anchors? No, Gemini doesn't know our DB.
            // Better: We append a note to Gemini to suggest where to link?
            // Or we just accept that 'Internal link suggestions' might be a separate job.
            // I'll stick to the current scope.
        }

        // Image Generation (Prompt)
        if (AiSetting::get('enable_image_gen') === '1' && !empty($content)) {
            $imagePrompt = $this->generateImagePrompt($topic);
            // Append the image prompt to the content as a comment or hidden field
            $content .= "\n<!-- SUGGESTED IMAGE PROMPT: {$imagePrompt} -->";
        }

        return $content;
    }

    public function generateFaq($topic) {
        $systemPrompt = "Generate 5 Frequently Asked Questions (FAQ) and their answers in Persian about the topic. Return as JSON array of objects {question, answer}.";
        $result = $this->gemini->generateContent($topic, $systemPrompt);
        return json_decode(str_replace(['```json', '```'], '', $result), true);
    }

    private function generateImagePrompt($topic) {
        $systemPrompt = "Generate a highly detailed English image generation prompt (for Midjourney/DALL-E) representing the topic: {$topic}.";
        return $this->gemini->generateContent("Topic: " . $topic, $systemPrompt);
    }

    private function fetchUrlContent($url) {
        // Basic scraping with validation
        if (!filter_var($url, FILTER_VALIDATE_URL)) return null;

        try {
            // Use cURL with browser headers
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            $html = curl_exec($ch);
            curl_close($ch);

            if (!$html) return null;

            // Strip tags to get text
            $text = strip_tags($html);
            // Compress whitespace
            $text = preg_replace('/\s+/', ' ', $text);
            return trim($text);
        } catch (\Exception $e) {
            AiLog::error("Failed to fetch URL: $url - " . $e->getMessage());
            return null;
        }
    }
}
