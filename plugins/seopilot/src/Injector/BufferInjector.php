<?php

namespace SeoPilot\Enterprise\Injector;

use App\Core\Database;
use SeoPilot\Enterprise\Service\SchemaGenerator;
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

            // 3. Inject using Regex (No DOMDocument for performance and stability)
            return self::inject($html, $metaData);

        } catch (\Throwable $e) {
            // Fail-safe: Log error and return original HTML to prevent breaking the site
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
            // Sanitize Title
            $title = OutputSanitizer::clean($metaData['title']);
            $injection .= "<title>{$title}</title>\n";
        }

        if (!empty($metaData['description'])) {
            // Sanitize Description
            $desc = OutputSanitizer::cleanDescription($metaData['description']);
            $injection .= "<meta name=\"description\" content=\"{$desc}\">\n";
        }

        if (!empty($metaData['json_ld'])) {
            // Normalize to list of schemas
            $schemas = isset($metaData['json_ld']['@context']) ? [$metaData['json_ld']] : $metaData['json_ld'];

            foreach ($schemas as $schema) {
                if ($schema) {
                    // Sanitize JSON
                    $json = OutputSanitizer::cleanJson($schema);
                    $injection .= "<script type=\"application/ld+json\">{$json}</script>\n";
                }
            }
        }

        // 4. Inject before </head>
        if ($injection) {
            // Use preg_replace to ensure only the first occurrence of </head> is targeted (case-insensitive)
            $html = preg_replace('/<\/head>/i', $injection . '</head>', $html, 1);
        }

        return $html;
    }

    /**
     * Resolve metadata based on current request URI
     */
    private static function resolveMetaData(array $context): ?array
    {
        $dbData = null;

        // 1. Try DB first (Manual Override)
        if ($context['type'] && $context['id']) {
            try {
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
            } catch (\Exception $e) {
                // Database error shouldn't crash the page, continue with generated schema
            }
        }

        // 2. Generate Schema (Automatic)
        // SchemaGenerator::generate should return an array of schemas or a single schema array
        $generatedSchema = [];
        if (class_exists(SchemaGenerator::class)) {
            $generatedSchema = SchemaGenerator::generate($context);
        }

        // 3. Merge Logic
        // If DB has custom schema, we append it to the generated schema (or merge if sophisticated)
        // Here we append, treating DB data as an additional block (e.g., custom Article schema on top of auto Breadcrumb)

        $finalJsonLd = [];

        // Add generated schema(s)
        if (!empty($generatedSchema)) {
             if (isset($generatedSchema['@context'])) {
                 $finalJsonLd[] = $generatedSchema;
             } else {
                 $finalJsonLd = array_merge($finalJsonLd, $generatedSchema);
             }
        }

        // Add DB schema(s)
        if ($dbData && !empty($dbData['json_ld'])) {
             if (isset($dbData['json_ld']['@context'])) {
                 $finalJsonLd[] = $dbData['json_ld'];
             } else {
                 $finalJsonLd = array_merge($finalJsonLd, $dbData['json_ld']);
             }
        }

        return [
            'title' => $dbData['title'] ?? null,
            'description' => $dbData['description'] ?? null,
            'json_ld' => $finalJsonLd,
            'context' => $context
        ];
    }

    private static function resolveContext(): array
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        $path = urldecode($path); // Handle Persian chars in URL

        // 1. Homepage
        if ($path === '/' || $path === '/index.php') {
            return ['type' => 'home', 'id' => null, 'slug' => null];
        }

        // 2. Blog Post: /blog/category/123-slug OR /blog/123-slug OR /blog/slug/123-slug (Legacy/Various)
        // Standard Pattern: /blog/{category_slug}/{id}-{post_slug}
        if (preg_match('#^/blog/(?:[^/]+/)?(\d+)-#', $path, $matches)) {
            return ['type' => 'post', 'id' => (int)$matches[1], 'slug' => null];
        }

        // 3. Product: /product/123-slug
        if (preg_match('#^/product/(\d+)-#', $path, $matches)) {
            return ['type' => 'product', 'id' => (int)$matches[1], 'slug' => null];
        }

        // 4. Categories
        // /category/slug
        if (preg_match('#^/category/([^/]+)#', $path, $matches)) {
            return ['type' => 'category', 'id' => null, 'slug' => $matches[1]];
        }
        // /blog/category/slug
        if (preg_match('#^/blog/category/([^/]+)#', $path, $matches)) {
            return ['type' => 'blog_category', 'id' => null, 'slug' => $matches[1]];
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
             return ['type' => 'blog_index', 'id' => null, 'slug' => 'blog-index'];
        }

        return ['type' => 'other', 'id' => null, 'slug' => null];
    }
}
