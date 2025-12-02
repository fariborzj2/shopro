<?php

namespace Plugins\AiNews\Services;

use DOMDocument;
use DOMXPath;

class FeedParser
{
    private $fetcher;

    public function __construct(Fetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function discoverUrls($url, $depth = 0)
    {
        if ($depth > 2) return [];

        $response = $this->fetcher->fetch($url);
        if (!$response['success']) {
            return [];
        }

        $content = $response['content'];
        $urls = [];

        // 1. Try XML Parsing
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);
        libxml_clear_errors();

        if ($xml) {
            $root = $xml->getName();

            // Sitemap Index
            if ($root === 'sitemapindex') {
                foreach ($xml->sitemap as $sitemap) {
                    $nestedUrls = $this->discoverUrls((string)$sitemap->loc, $depth + 1);
                    $urls = array_merge($urls, $nestedUrls);
                }
            }
            // Sitemap
            elseif ($root === 'urlset') {
                foreach ($xml->url as $urlNode) {
                    if (isset($urlNode->loc)) $urls[] = (string)$urlNode->loc;
                }
            }
            // RSS
            elseif ($root === 'rss' || isset($xml->channel)) {
                foreach ($xml->channel->item as $item) {
                    if (isset($item->link)) $urls[] = (string)$item->link;
                }
            }
            // Atom
            elseif ($root === 'feed') {
                foreach ($xml->entry as $entry) {
                    if (isset($entry->link)) {
                        foreach ($entry->link as $link) {
                            $attrs = $link->attributes();
                            if (!isset($attrs['rel']) || (string)$attrs['rel'] === 'alternate') {
                                $urls[] = (string)$attrs['href'];
                                break;
                            }
                        }
                    }
                }
            }
        }
        else {
            // 2. Fallback: Regex for simple XML tags
            preg_match_all('/<(?:loc|link)>(.*?)<\/(?:loc|link)>/i', $content, $matches);
            if (!empty($matches[1])) {
                $urls = array_merge($urls, $matches[1]);
            }

            // 3. Fallback: HTML Links (if user provided a direct page URL)
            // Only try this if regex failed and content looks like HTML
            if (empty($urls) && stripos($content, '<html') !== false) {
                $dom = new DOMDocument();
                @$dom->loadHTML($content);
                $xpath = new DOMXPath($dom);
                // Look for article links (often inside h2/h3 or with specific classes)
                $links = $xpath->query('//a[contains(@href, "http")]'); // Basic filter
                foreach ($links as $link) {
                    $href = $link->getAttribute('href');
                    // Basic filtering to avoid junk
                    if (filter_var($href, FILTER_VALIDATE_URL) && strlen($href) > 20) {
                        $urls[] = $href;
                    }
                }
            }
        }

        // Clean and Normalize
        return array_values(array_unique(array_filter(array_map('trim', $urls), function($u) {
            return filter_var($u, FILTER_VALIDATE_URL);
        })));
    }
}
