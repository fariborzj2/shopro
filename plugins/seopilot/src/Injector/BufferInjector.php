<?php

namespace SeoPilot\Enterprise\Injector;

use App\Core\Database;
use SeoPilot\Enterprise\Analyzer\PixelAnalyzer;
use SeoPilot\Enterprise\NLP\PersianProcessor;

class BufferInjector
{
    /**
     * Main handler for Output Buffering
     * Called by ob_start logic
     */
    public static function handle(string $html): string
    {
        // Fail-safe: If HTML is too short or not HTML, return as is
        if (strlen($html) < 50 || stripos($html, '<html') === false) {
            return $html;
        }

        try {
            // 1. Identify Context (Entity Type & ID)
            $metaData = self::resolveMetaData();

            // Check AutoMeta logic if no DB data found or if fields are missing
            $settings = self::getOptions();
            if ($settings['ai_auto_meta'] ?? false) {
                 if (!$metaData) {
                     $metaData = [];
                 }
                 // Generate missing parts on the fly using DOM analysis
                 $metaData = self::generateAutoMeta($html, $metaData);
            }

            if (!$metaData || (empty($metaData['title']) && empty($metaData['description']))) {
                return $html;
            }

            // 2. Parse DOM
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            // Hack for UTF-8 in loadHTML
            $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();

            $head = $dom->getElementsByTagName('head')->item(0);
            if (!$head) {
                return $html;
            }

            // 3. Deduplication & Injection
            if (!empty($metaData['title'])) {
                $titles = $dom->getElementsByTagName('title');
                while ($titles->length > 0) {
                    $titles->item(0)->parentNode->removeChild($titles->item(0));
                }

                $newTitle = $dom->createElement('title', $metaData['title']);
                $head->insertBefore($newTitle, $head->firstChild);
            }

            if (!empty($metaData['description'])) {
                // Remove existing meta desc
                $metas = $dom->getElementsByTagName('meta');
                $nodesToRemove = [];
                foreach ($metas as $meta) {
                    if ($meta->getAttribute('name') === 'description') {
                        $nodesToRemove[] = $meta;
                    }
                }
                foreach ($nodesToRemove as $node) {
                    $node->parentNode->removeChild($node);
                }

                $newDesc = $dom->createElement('meta');
                $newDesc->setAttribute('name', 'description');
                $newDesc->setAttribute('content', $metaData['description']);
                $head->appendChild($newDesc);
            }

            // 4. Inject JSON-LD
            if (!empty($metaData['json_ld'])) {
                $script = $dom->createElement('script');
                $script->setAttribute('type', 'application/ld+json');
                $script->nodeValue = json_encode($metaData['json_ld'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $head->appendChild($script);
            }

            return $dom->saveHTML();

        } catch (\Throwable $e) {
            // Fail-safe
            return $html;
        }
    }

    /**
     * Resolve metadata based on current request URI
     */
    private static function resolveMetaData()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = strtok($uri, '?');

        $entityType = null;
        $entityId = null;

        // Simple Regex Routing
        if (preg_match('#^/blog/(?:[^/]+/)?(\d+)-#', $uri, $matches)) {
            $entityType = 'post';
            $entityId = $matches[1];
        } elseif (preg_match('#^/product/(\d+)-#', $uri, $matches)) {
            $entityType = 'product';
            $entityId = $matches[1];
        }

        if ($entityType && $entityId) {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM seopilot_meta WHERE entity_type = ? AND entity_id = ?");
            $stmt->execute([$entityType, $entityId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($row) {
                $data = json_decode($row['data_raw'], true);
                return [
                    'title' => $data['title'] ?? null,
                    'description' => $data['description'] ?? null,
                    'json_ld' => $data['json_ld'] ?? null
                ];
            }
        }

        return null;
    }

    private static function getOptions()
    {
        // Simple file cache or static var could optimize this
        static $options = null;
        if ($options !== null) return $options;

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT option_value FROM seopilot_options WHERE option_name = 'settings'");
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            $options = $row ? json_decode($row['option_value'], true) : [];
        } catch (\Exception $e) {
            $options = [];
        }
        return $options;
    }

    /**
     * Generate AutoMeta on the fly if missing
     */
    private static function generateAutoMeta($html, $currentMeta)
    {
        // If we already have both, return
        if (!empty($currentMeta['title']) && !empty($currentMeta['description'])) {
            return $currentMeta;
        }

        // Analyze DOM
        $metrics = PixelAnalyzer::analyzeContent($html);

        // Extract Title from H1 if missing
        if (empty($currentMeta['title'])) {
            // Quick regex to find H1 because parsing full DOM again is heavy?
            // We already have HTML. Let's use a lightweight check.
            if (preg_match('/<h1[^>]*>(.*?)<\/h1>/si', $html, $matches)) {
                $h1 = strip_tags($matches[1]);
                $h1 = PersianProcessor::normalize($h1);
                if (!empty($h1)) {
                    $currentMeta['title'] = $h1;
                }
            }
        }

        // Extract Description from P if missing
        if (empty($currentMeta['description'])) {
            // Find first paragraph with substantial text
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();

            $paragraphs = $dom->getElementsByTagName('p');
            foreach ($paragraphs as $p) {
                $text = trim($p->textContent);
                if (mb_strlen($text) > 50) {
                    // Normalize and truncate
                    $text = PersianProcessor::normalize($text);
                    // Take first 160 chars approx
                    if (mb_strlen($text) > 160) {
                        $text = mb_substr($text, 0, 157) . '...';
                    }
                    $currentMeta['description'] = $text;
                    break;
                }
            }
        }

        return $currentMeta;
    }
}
