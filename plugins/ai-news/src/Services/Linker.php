<?php

namespace Plugins\AiNews\Services;

use App\Core\Database;
use PDO;
use DOMDocument;
use DOMXPath;

class Linker
{
    private $existingPosts = [];

    public function __construct()
    {
        // Pre-load titles and links to avoid N+1 queries
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id, title, slug FROM blog_posts WHERE status = 'published'");
        $this->existingPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function injectLinks($content)
    {
        // Check for empty content or missing libxml
        if (empty($content) || !class_exists('DOMDocument')) {
            return $content;
        }

        // Sort by title length desc to match longest phrases first
        usort($this->existingPosts, function($a, $b) {
            return strlen($b['title']) - strlen($a['title']);
        });

        // Suppress warnings for HTML5 elements (DOMDocument is old)
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        // Use a wrapper to ensure encoding is handled correctly
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $textNodes = $xpath->query('//text()[not(ancestor::a) and not(ancestor::script) and not(ancestor::style)]');

        $maxLinks = 3;
        $linksAdded = 0;
        $usedTitles = [];

        foreach ($textNodes as $node) {
            if ($linksAdded >= $maxLinks) break;

            $text = $node->nodeValue;
            $newText = $text;
            $modified = false;

            foreach ($this->existingPosts as $post) {
                if ($linksAdded >= $maxLinks) break;
                if (in_array($post['title'], $usedTitles)) continue;

                $title = $post['title'];
                // Check if title exists in this text node (case insensitive for english, exact for persian)
                // Use mb_stripos for multibyte support
                $pos = mb_stripos($newText, $title);

                if ($pos !== false) {
                    // Found a match in this text node
                    // We need to split the text node and insert an anchor
                    // Since DOM modification inside the loop is tricky, we'll mark it and replace later?
                    // Actually, simplest way with DOM is to replace the text node with a fragment

                    // Create replacement fragment
                    $fragment = $dom->createDocumentFragment();

                    // Text before match
                    $before = mb_substr($newText, 0, $pos);
                    if ($before) $fragment->appendChild($dom->createTextNode($before));

                    // Link
                    $a = $dom->createElement('a');
                    $a->setAttribute('href', "/blog/{$post['slug']}");
                    $a->setAttribute('target', '_blank');
                    $a->setAttribute('class', 'text-primary-600 hover:underline');
                    $a->nodeValue = $title;
                    $fragment->appendChild($a);

                    // Text after match
                    $after = mb_substr($newText, $pos + mb_strlen($title));
                    if ($after) $fragment->appendChild($dom->createTextNode($after)); // Note: this might contain more keywords, but we skip for simplicity/safety

                    $node->parentNode->replaceChild($fragment, $node);

                    $linksAdded++;
                    $usedTitles[] = $title;
                    $modified = true;

                    // Break inner loop (posts) to restart search on next text node?
                    // No, we just modified the node so we must break the loop for this node.
                    break;
                }
            }

            if ($modified) {
                // If we modified the DOM, the NodeList ($textNodes) might be stale if we were iterating strictly.
                // However, foreach on a query result is usually safe in PHP DOM if we just replace the current node.
                // But complex replacements might require re-querying.
                // Given we break after one replacement per node, it's safer.
            }
        }

        // Save HTML
        // saveHTML returns the wrapper we added? No, we used NOIMPLIED.
        // But the encoding wrapper might be there?
        // Let's strip the wrapper if present.
        $output = $dom->saveHTML();
        $output = str_replace('<?xml encoding="utf-8" ?>', '', $output);

        return trim($output);
    }
}
