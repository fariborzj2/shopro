<?php

namespace SeoPilot\Enterprise\Service;

use SeoPilot\Enterprise\NLP\PersianProcessor;

class AutoFixer
{
    /**
     * Fix missing metadata using DOM Analysis
     * Optimized to reuse DOMDocument if possible, or parse string.
     *
     * @param array $currentMeta
     * @param mixed $htmlSource string HTML or \DOMDocument
     * @return array
     */
    public static function fix(array $currentMeta, $htmlSource): array
    {
        $dom = null;
        if ($htmlSource instanceof \DOMDocument) {
            $dom = $htmlSource;
        } else {
            // If string passed, parse it
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($htmlSource, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();
        }

        $xpath = new \DOMXPath($dom);

        // 1. Fix Title
        if (empty($currentMeta['title'])) {
            $h1Nodes = $xpath->query('//h1');
            if ($h1Nodes->length > 0) {
                $h1 = strip_tags($h1Nodes->item(0)->textContent);
                $h1 = PersianProcessor::normalize($h1);
                if (!empty($h1)) {
                    $currentMeta['title'] = $h1;
                }
            }
        }

        // 2. Fix Description
        if (empty($currentMeta['description'])) {
            $currentMeta['description'] = self::extractDescription($xpath);
        }

        return $currentMeta;
    }

    private static function extractDescription(\DOMXPath $xpath): string
    {
        // Look for paragraphs with substantial text
        $paragraphs = $xpath->query('//p');

        foreach ($paragraphs as $p) {
            $text = trim($p->textContent);
            if (mb_strlen($text) > 50) {
                $text = PersianProcessor::normalize($text);
                // Truncate
                if (mb_strlen($text) > 160) {
                    $text = mb_substr($text, 0, 157) . '...';
                }
                return $text;
            }
        }

        return '';
    }
}
