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
            "Your task is to generate content based on the user request. " .
            "Strictly adhere to the following rules:\n" .
            "1. Output Language: {$language} ONLY.\n";

        if (($options['format'] ?? '') === 'title') {
            $systemPrompt .= "2. Format: Return ONLY the title text, no HTML, no quotes, no markdown.\n" .
                             "3. Goal: Write a catchy, SEO-friendly headline for the topic.\n";
        } else {
            $systemPrompt .= "2. Structure: Use H2 and H3 tags for headings. Do not use H1.\n" .
                             "3. Format: Return pure HTML content (paragraphs, lists, headings) without ```html``` code blocks or markdown.\n" .
                             "4. Tone: Professional yet accessible.\n" .
                             "5. Length: " . (($options['length'] ?? '') === 'short' ? 'Short and concise.' : 'Comprehensive (approx 1000-1500 words).') . "\n" .
                             "6. Content: informative, valuable, and original.\n";
        }

        if (!empty($options['keywords'])) {
            $systemPrompt .= "\n7. Focus Keywords: " . implode(', ', $options['keywords']);
        }

        if (AiSetting::get('enable_faq_gen') === '1') {
             $systemPrompt .= "\n8. Add a FAQ section at the end with 3-5 questions and answers.";
        }

        $userPrompt = "Write an article about: " . $topic;

        if (!empty($options['instruction'])) {
            $userPrompt = "INSTRUCTION: " . $options['instruction'] . "\nCONTEXT: " . $topic;
        }

        if ($sourceContext) {
            $userPrompt .= "\n\n" . $sourceContext;
        }

        $content = $this->gemini->generateContent($userPrompt, $systemPrompt);

        // Internal Links Injection
        if (AiSetting::get('enable_internal_links') === '1' && !empty($content) && ($options['format'] ?? '') !== 'title') {
            $content = $this->injectInternalLinks($content);
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

    private function injectInternalLinks($html) {
        if (empty($html)) return $html;

        try {
            $posts = \App\Models\BlogPost::getAllPublished();
            if (empty($posts)) return $html;

            // Sort posts by title length DESC to match longest phrases first
            usort($posts, function($a, $b) {
                return mb_strlen($b['title'] ?? '') <=> mb_strlen($a['title'] ?? '');
            });

            // Use DOMDocument for safe manipulation
            $dom = new \DOMDocument();
            // Suppress warnings for HTML5 tags or malformed HTML from AI
            libxml_use_internal_errors(true);

            // Add mb_convert_encoding to handle UTF-8 properly with DOMDocument
            $encodedHtml = '<?xml encoding="utf-8" ?>' . $html;
            $dom->loadHTML($encodedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();

            $linkCount = 0;
            $maxLinks = (int) AiSetting::get('max_internal_links', 5);

            $xpath = new \DOMXPath($dom);

            foreach ($posts as $post) {
                if ($linkCount >= $maxLinks) break;

                $title = trim($post['title']);
                if (mb_strlen($title) < 4) continue;

                $url = "/blog/" . ($post['category_slug'] ?? 'uncategorized') . "/{$post['id']}-{$post['slug']}";

                // Find text nodes that contain the title and are NOT inside <a> or headings
                // We target <p>, <li>, <div>, <span>
                $query = "//text()[contains(., '$title') and not(ancestor::a) and not(ancestor::h1) and not(ancestor::h2) and not(ancestor::h3) and not(ancestor::h4)]";
                $nodes = $xpath->query($query);

                foreach ($nodes as $node) {
                    if ($linkCount >= $maxLinks) break;

                    $text = $node->nodeValue;
                    // Strict case-insensitive match for Persian/Arabic
                    if (mb_stripos($text, $title) !== false) {
                        // Split text and inject anchor
                        $parts = preg_split('/(' . preg_quote($title, '/') . ')/ui', $text, 2, PREG_SPLIT_DELIM_CAPTURE);

                        if (count($parts) === 3) {
                            $newFragment = $dom->createDocumentFragment();
                            $newFragment->appendChild($dom->createTextNode($parts[0]));

                            $a = $dom->createElement('a', htmlspecialchars($parts[1]));
                            $a->setAttribute('href', $url);
                            $a->setAttribute('class', 'text-primary-600 hover:underline font-bold ai-internal-link');
                            $newFragment->appendChild($a);

                            $newFragment->appendChild($dom->createTextNode($parts[2]));

                            $node->parentNode->replaceChild($newFragment, $node);
                            $linkCount++;
                        }
                    }
                }
            }

            $html = $dom->saveHTML();
            // Remove the xml tag we added
            $html = str_replace('<?xml encoding="utf-8" ?>', '', $html);

        } catch (\Exception $e) {
            AiLog::error("Advanced Internal linking failed: " . $e->getMessage());
        }

        return $html;
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
