<?php

namespace SeoPilot\Enterprise\Injector;

use App\Core\Database;
use SeoPilot\Enterprise\Service\AutoFixer;
use SeoPilot\Enterprise\Cache\CacheManager;
use SeoPilot\Enterprise\Security\OutputSanitizer;

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
            // 1. Parse DOM Once
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            // Hack for UTF-8 in loadHTML
            $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();

            // 2. Identify Context (Entity Type & ID)
            $metaData = self::resolveMetaData();

            // 3. AutoFixer (AutoMeta) with DOM reuse
            $settings = self::getOptions();
            if ($settings['ai_auto_meta'] ?? false) {
                 if (!$metaData) {
                     $metaData = ['title' => '', 'description' => '', 'json_ld' => []];
                 }
                 // Fix missing parts reusing DOM
                 $metaData = AutoFixer::fix($metaData, $dom);
            }

            if (empty($metaData['title']) && empty($metaData['description'])) {
                return $html;
            }

            // 4. Inject into the existing DOM
            return self::inject($dom, $metaData);

        } catch (\Throwable $e) {
            // Fail-safe
            return $html;
        }
    }

    /**
     * Core Injection Logic
     * @param mixed $htmlSource \DOMDocument or string (for BC/Testing)
     * @param array $metaData
     */
    public static function inject($htmlSource, array $metaData): string
    {
        $dom = null;
        if ($htmlSource instanceof \DOMDocument) {
            $dom = $htmlSource;
        } else {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($htmlSource, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();
        }

        $head = $dom->getElementsByTagName('head')->item(0);
        if (!$head) {
            // Create head if missing (rare)
            $head = $dom->createElement('head');
            $htmlNode = $dom->getElementsByTagName('html')->item(0);
            if ($htmlNode) {
                $htmlNode->insertBefore($head, $htmlNode->firstChild);
            } else {
                return $dom->saveHTML();
            }
        }

        // Deduplication & Injection

        // 1. Title
        if (!empty($metaData['title'])) {
            $sanitizedTitle = OutputSanitizer::clean($metaData['title']);

            $titles = $dom->getElementsByTagName('title');
            while ($titles->length > 0) {
                $titles->item(0)->parentNode->removeChild($titles->item(0));
            }

            $newTitle = $dom->createElement('title', $sanitizedTitle);
            $head->insertBefore($newTitle, $head->firstChild);
        }

        // 2. Description
        if (!empty($metaData['description'])) {
            $sanitizedDesc = OutputSanitizer::cleanDescription($metaData['description']);

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
            $newDesc->setAttribute('content', $sanitizedDesc);
            $head->appendChild($newDesc);
        }

        // 3. JSON-LD
        if (!empty($metaData['json_ld'])) {
            $jsonString = OutputSanitizer::cleanJson($metaData['json_ld']);

            $script = $dom->createElement('script');
            $script->setAttribute('type', 'application/ld+json');
            $script->appendChild($dom->createTextNode($jsonString));
            $head->appendChild($script);
        }

        // 4. LiteSpeed Headers
        $context = self::resolveContext();
        if ($context['entityType'] && $context['entityId']) {
            $cache = new CacheManager();
            $cache->setTags(["{$context['entityType']}_{$context['entityId']}"]);
        }

        // Return clean HTML (decode entities if possible, but keep structure)
        // Since we loaded with HTML-ENTITIES, saveHTML might output entities.
        // We'll trust standard saveHTML for now as full decoding can be risky for some chars.
        // But we ensure no Double-Encoding happens.
        return $dom->saveHTML();
    }

    /**
     * Resolve metadata based on current request URI
     */
    private static function resolveMetaData()
    {
        $ctx = self::resolveContext();
        if ($ctx['entityType'] && $ctx['entityId']) {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM seopilot_meta WHERE entity_type = ? AND entity_id = ?");
            $stmt->execute([$ctx['entityType'], $ctx['entityId']]);
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

    private static function resolveContext()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = strtok($uri, '?');

        $entityType = null;
        $entityId = null;

        if (preg_match('#^/blog/(?:[^/]+/)?(\d+)-#', $uri, $matches)) {
            $entityType = 'post';
            $entityId = $matches[1];
        } elseif (preg_match('#^/product/(\d+)-#', $uri, $matches)) {
            $entityType = 'product';
            $entityId = $matches[1];
        }

        return ['entityType' => $entityType, 'entityId' => $entityId];
    }

    private static function getOptions()
    {
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
}
