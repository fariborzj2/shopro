<?php

namespace SeoPilot\Enterprise\Injector;

use App\Core\Database;
// use SeoPilot\Enterprise\Service\AutoFixer;
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
        if (strlen($html) < 50 || stripos($html, '<head') === false) {
            return $html;
        }

        try {
            // 1. Resolve Metadata
            $metaData = self::resolveMetaData();

            // AutoFixer disabled for stability (DOMDocument causes issues)
            // if ($settings['ai_auto_meta'] ?? false) { ... }

            if (!$metaData) {
                return $html;
            }

            // 2. Inject using Regex (No DOMDocument)
            return self::inject($html, $metaData);

        } catch (\Throwable $e) {
            // Fail-safe
            return $html;
        }
    }

    /**
     * Core Injection Logic
     * @param string $html
     * @param array $metaData
     */
    public static function inject(string $html, array $metaData): string
    {
        // 1. Remove existing Title if we have a new one
        if (!empty($metaData['title'])) {
            $html = preg_replace('/<title[^>]*>.*?<\/title>/is', '', $html);
        }

        // 2. Remove existing Description if we have a new one
        if (!empty($metaData['description'])) {
            $html = preg_replace('/<meta[^>]+name=["\']description["\'][^>]*>/i', '', $html);
        }

        // 3. Prepare Injection String
        $injection = '';

        if (!empty($metaData['title'])) {
            $title = OutputSanitizer::clean($metaData['title']);
            $injection .= "<title>{$title}</title>\n";
        }

        if (!empty($metaData['description'])) {
            $desc = OutputSanitizer::cleanDescription($metaData['description']);
            $injection .= "<meta name=\"description\" content=\"{$desc}\">\n";
        }

        if (!empty($metaData['json_ld'])) {
            $json = OutputSanitizer::cleanJson($metaData['json_ld']);
            $injection .= "<script type=\"application/ld+json\">{$json}</script>\n";
        }

        // 4. Inject before </head>
        if ($injection) {
            $html = preg_replace('/<\/head>/i', $injection . '</head>', $html, 1);
        }

        // 5. LiteSpeed Headers
        $context = self::resolveContext();
        if ($context['entityType'] && $context['entityId']) {
            $cache = new CacheManager();
            $cache->setTags(["{$context['entityType']}_{$context['entityId']}"]);
        }

        return $html;
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
