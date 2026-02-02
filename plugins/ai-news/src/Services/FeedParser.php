<?php

namespace AiNews\Services;

class FeedParser {
    public function parse($xml, $type = 'rss') {
        if (empty($xml)) return [];

        try {
            $libxml_use_internal_errors = libxml_use_internal_errors(true);
            $sxe = simplexml_load_string($xml);
            libxml_use_internal_errors($libxml_use_internal_errors);

            if ($sxe === false) {
                 // Try regex fallback if XML is malformed
                 return $this->parseRegex($xml);
            }

            $items = [];

            // RSS 2.0
            if (isset($sxe->channel->item)) {
                foreach ($sxe->channel->item as $item) {
                    $items[] = [
                        'title' => (string) $item->title,
                        'url' => (string) $item->link,
                        'pubDate' => (string) $item->pubDate
                    ];
                }
            }
            // Atom
            elseif (isset($sxe->entry)) {
                foreach ($sxe->entry as $entry) {
                    $items[] = [
                        'title' => (string) $entry->title,
                        'url' => (string) $entry->link['href'],
                        'pubDate' => (string) ($entry->updated ?? $entry->published)
                    ];
                }
            }
            // Sitemap
            elseif (isset($sxe->url)) {
                 foreach ($sxe->url as $url) {
                    $items[] = [
                        'title' => '', // Sitemap doesn't have titles
                        'url' => (string) $url->loc,
                        'pubDate' => (string) ($url->lastmod ?? '')
                    ];
                }
            }

            return $items;
        } catch (\Exception $e) {
            return $this->parseRegex($xml);
        }
    }

    private function parseRegex($xml) {
        $items = [];
        // Extract <link> contents as a fallback
        preg_match_all('/<link[^>]*>(.*?)<\/link>/is', $xml, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $url) {
                $url = trim(strip_tags($url));
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $items[] = ['url' => $url, 'title' => ''];
                }
            }
        }
        return $items;
    }
}
