<?php

namespace SeoPilot\Enterprise\Injector;

use App\Core\Database;
// use SeoPilot\Enterprise\Service\AutoFixer; // Disabled for stability
use SeoPilot\Enterprise\Service\SchemaGenerator;
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
            // 1. Resolve Context
            $context = self::resolveContext();

            // 2. Resolve Metadata (DB or Generate)
            $metaData = self::resolveMetaData($context);

            if (!$metaData) {
                return $html;
            }

            // 3. Inject using Regex (No DOMDocument)
            return self::inject($html, $metaData);

        } catch (\Throwable $e) {
            // Fail-safe: log error if possible, otherwise return original
            // error_log("SeoPilot Injection Error: " . $e->getMessage());
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
        // 1. Remove existing Title if we have a new one (DB override)
        // If generated (not from DB), we might want to KEEP the original title if we didn't generate one,
        // but here we assume if we have metadata, we use it.
        if (!empty($metaData['title'])) {
            $html = preg_replace('/<title[^>]*>.*?<\/title>/is', '', $html);
        }

        // 2. Remove existing Description
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
            // json_ld can be an array (multiple schemas) or a single array
            // Normalize to list of scripts or single script
            $schemas = isset($metaData['json_ld']['@context']) ? [$metaData['json_ld']] : $metaData['json_ld'];

            foreach ($schemas as $schema) {
                if ($schema) {
                    $json = OutputSanitizer::cleanJson($schema);
                    $injection .= "<script type=\"application/ld+json\">{$json}</script>\n";
                }
            }
        }

        // 4. Inject before </head>
        if ($injection) {
            $html = preg_replace('/<\/head>/i', $injection . '</head>', $html, 1);
        }

        // 5. LiteSpeed Headers
        if (!empty($metaData['context']) && isset($metaData['context']['type']) && isset($metaData['context']['id'])) {
             // Optional cache tagging
             // $cache = new CacheManager();
             // $cache->setTags(["{$metaData['context']['type']}_{$metaData['context']['id']}"]);
        }

        return $html;
    }

    /**
     * Resolve metadata based on current request URI
     */
    private static function resolveMetaData($context)
    {
        // 1. Try DB first (Manual Override)
        $dbData = null;
        if ($context['type'] && $context['id']) {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM seopilot_meta WHERE entity_type = ? AND entity_id = ?");
            $stmt->execute([$context['type'], $context['id']]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($row) {
                $data = json_decode($row['data_raw'], true);
                $dbData = [
                    'title' => $data['title'] ?? null,
                    'description' => $data['description'] ?? null,
                    'json_ld' => $data['json_ld'] ?? null
                ];
            }
        }

        // 2. Generate Schema (Automatic)
        // Always generate schema to ensure full coverage (Breadcrumbs etc),
        // merging with DB overrides if necessary.
        // For now, if DB provides json_ld, we might trust it fully or merge.
        // Requirement: "SeoPilot must become the only authoritative generator... fully automatic"
        // Better approach: Generate base schema, then overlay DB overrides.

        $generatedSchema = SchemaGenerator::generate($context);

        // If DB has specific JSON-LD, maybe append or replace?
        // Usually DB override means "User wants this specific schema".
        // But Breadcrumbs should always be there.
        // Let's Append DB schema to Generated Schema if DB schema exists.

        $finalJsonLd = $generatedSchema;
        if ($dbData && !empty($dbData['json_ld'])) {
             // If DB has custom schema, add it to the list
             // Or if strictly overriding, maybe replace?
             // Safest: Add to array.
             if (isset($dbData['json_ld']['@context'])) {
                 $finalJsonLd[] = $dbData['json_ld'];
             } else {
                 $finalJsonLd = array_merge($finalJsonLd, $dbData['json_ld']);
             }
        }

        return [
            'title' => $dbData['title'] ?? null, // Use DB title or keep original (handled in inject)
            'description' => $dbData['description'] ?? null,
            'json_ld' => $finalJsonLd,
            'context' => $context
        ];
    }

    private static function resolveContext()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        $path = urldecode($path); // Handle Persian chars in URL

        $type = null;
        $id = null;
        $slug = null;

        // 1. Homepage
        if ($path === '/' || $path === '/index.php') {
            return ['type' => 'home', 'id' => null, 'slug' => null];
        }

        // 2. Blog Post: /blog/category/123-slug OR /blog/123-slug
        if (preg_match('#^/blog/(?:[^/]+/)?(\d+)-#', $path, $matches)) {
            return ['type' => 'post', 'id' => $matches[1], 'slug' => null];
        }

        // 3. Product: /product/123-slug
        if (preg_match('#^/product/(\d+)-#', $path, $matches)) {
            return ['type' => 'product', 'id' => $matches[1], 'slug' => null];
        }

        // 4. Categories
        // /category/slug
        if (preg_match('#^/category/([^/]+)#', $path, $matches)) {
            return ['type' => 'category', 'id' => null, 'slug' => $matches[1]];
        }
        // /blog/category/slug
        if (preg_match('#^/blog/category/([^/]+)#', $path, $matches)) {
            return ['type' => 'category', 'id' => null, 'slug' => $matches[1]];
        }

        // 5. Pages
        // /page/slug
        if (preg_match('#^/page/([^/]+)#', $path, $matches)) {
            return ['type' => 'page', 'id' => null, 'slug' => $matches[1]];
        }
        // /faq (Special Page)
        if ($path === '/faq') {
            return ['type' => 'page', 'id' => null, 'slug' => 'faq'];
        }

        // 6. Tags
        // /blog/tags/slug
        if (preg_match('#^/blog/tags/([^/]+)#', $path, $matches)) {
            return ['type' => 'tag', 'id' => null, 'slug' => $matches[1]];
        }

        // 7. General Blog Index
        if ($path === '/blog') {
             // Treat as a collection page for blog
             // We can map this to a special category or just 'blog' type
             return ['type' => 'category', 'id' => null, 'slug' => 'blog-index'];
             // Note: slug 'blog-index' might not match DB, but SchemaGenerator can handle it gracefully?
             // Actually SchemaGenerator checks DB for slug.
             // Let's leave it as generic or handle specifically.
        }

        return ['type' => 'other', 'id' => null, 'slug' => null];
    }
}
