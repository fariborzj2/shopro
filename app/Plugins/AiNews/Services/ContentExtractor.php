<?php

namespace App\Plugins\AiNews\Services;

use DOMDocument;
use DOMXPath;

class ContentExtractor
{
    public function extract($html)
    {
        if (empty($html)) return null;

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        // UTF-8 Hack
        @$doc->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($doc);

        // Remove noise
        $noiseSelectors = [
            '//script', '//style', '//header', '//footer', '//nav',
            '//aside', '//form', '//iframe',
            '//*[contains(@class, "comment")]',
            '//*[contains(@class, "ad")]',
            '//*[contains(@class, "share")]',
            '//*[contains(@class, "related")]'
        ];

        foreach ($noiseSelectors as $selector) {
            foreach ($xpath->query($selector) as $node) {
                $node->parentNode->removeChild($node);
            }
        }

        // Extract Title
        $title = '';
        $metaTitle = $xpath->query('//meta[@property="og:title"]/@content');
        if ($metaTitle->length > 0) $title = $metaTitle->item(0)->nodeValue;
        if (!$title) {
            $h1 = $xpath->query('//h1');
            if ($h1->length > 0) $title = $h1->item(0)->nodeValue;
        }

        // Extract Image
        $image = '';
        $metaImage = $xpath->query('//meta[@property="og:image"]/@content');
        if ($metaImage->length > 0) $image = $metaImage->item(0)->nodeValue;

        // Extract Content
        $content = '';
        // Try semantic tags first
        $article = $xpath->query('//article');
        $rootNode = $article->length > 0 ? $article->item(0) : $doc;

        // Get paragraphs, headings, blockquotes, lists
        $nodes = $xpath->query('.//p | .//h2 | .//h3 | .//blockquote | .//ul | .//ol', $rootNode);

        foreach ($nodes as $node) {
            $text = trim($node->nodeValue);
            if (mb_strlen($text) < 20 && !in_array($node->nodeName, ['img', 'ul', 'ol'])) continue; // Skip short junk

            // Re-export HTML of the node to preserve formatting
            $content .= $doc->saveHTML($node);
        }

        // Minimum length check
        if (mb_strlen(strip_tags($content)) < 300) {
            return null;
        }

        return [
            'title' => trim($title),
            'content' => $content,
            'image_url' => $image,
            'meta_description' => '' // Can extract from meta description tag if needed
        ];
    }
}
