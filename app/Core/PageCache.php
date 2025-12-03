<?php

namespace App\Core;

class PageCache
{
    // A unique placeholder to replace the real CSRF token in cached content
    const CSRF_PLACEHOLDER = '<!--CSRF_TOKEN_PLACEHOLDER-->';

    /**
     * Attempt to serve the cached page.
     * This should be called before any router logic.
     */
    public static function serve()
    {
        // 1. Check if Guest (Cached pages are ONLY for guests)
        if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            return;
        }

        // 2. Check if GET request
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return;
        }

        // 3. Connect to Cache
        try {
            $cache = Cache::getInstance();
        } catch (\Exception $e) {
            return;
        }

        // 4. Check Settings
        // In a high-perf scenario, we'd cache these settings flags too.
        // For now, we query.
        $settings = \App\Models\Setting::getAll();

        $enabled = isset($settings['cache_html_enabled']) && $settings['cache_html_enabled'] === '1';
        if (!$enabled) {
            return;
        }

        // 5. Check Excluded URLs
        $uri = $_SERVER['REQUEST_URI'];
        $excludedPatterns = isset($settings['cache_excluded_urls']) ? explode("\n", $settings['cache_excluded_urls']) : [];
        foreach ($excludedPatterns as $pattern) {
            $pattern = trim($pattern);
            if (empty($pattern)) continue;
            if (fnmatch($pattern, $uri)) {
                return;
            }
        }

        // 6. Generate Key
        $cacheKey = 'page_' . md5($uri);

        // 7. Fetch Content
        $content = $cache->get($cacheKey);

        if ($content) {
            // 8. Inject Fresh CSRF Token
            // If the cached content has the placeholder, we replace it with the current session's token.
            if (strpos($content, self::CSRF_PLACEHOLDER) !== false) {
                // Ensure we have a token for this guest session
                $token = '';
                if (function_exists('csrf_token')) {
                    $token = csrf_token();
                } elseif (isset($_SESSION['csrf_token'])) {
                    $token = $_SESSION['csrf_token'];
                }

                // If for some reason token generation fails, we might output empty,
                // but usually csrf_token() handles it.
                $content = str_replace(self::CSRF_PLACEHOLDER, $token, $content);
            }

            // Add Cache Hit Header
            header('X-Cache: HIT');
            echo $content;
            exit();
        }
    }

    /**
     * Start capturing output for caching.
     */
    public static function start()
    {
        // Only if Guest + GET
        if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'GET') {
            return false;
        }

        ob_start();
        return true;
    }

    /**
     * End capturing and save to cache.
     */
    public static function end($tags = [])
    {
        // Only if buffering is active
        if (ob_get_level() < 1) {
            return;
        }

        $content = ob_get_flush(); // Flush and return content to user (and capture it)

        // Re-verify guest/method
        if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'GET') {
            return;
        }

        $settings = \App\Models\Setting::getAll();
        $enabled = isset($settings['cache_html_enabled']) && $settings['cache_html_enabled'] === '1';

        if ($enabled) {
            // Process Content: Replace CSRF tokens with Placeholder
            // We look for the standard input value pattern or the logic used by csrf_field()
            // Pattern: name="csrf_token" value="..."

            // We use a regex to safely find the value attribute of the csrf_token input
            $pattern = '/(name="csrf_token"\s+value=")([^"]+)(")/i';

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, '$1' . self::CSRF_PLACEHOLDER . '$3', $content);
            }

            $ttl = (int)($settings['cache_html_ttl'] ?? 600);
            $uri = $_SERVER['REQUEST_URI'];
            $cacheKey = 'page_' . md5($uri);

            Cache::getInstance()->put($cacheKey, $content, $ttl, $tags);
        }
    }
}
