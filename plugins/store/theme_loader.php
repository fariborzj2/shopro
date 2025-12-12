<?php

use App\Models\Setting;

if (!function_exists('load_theme_view')) {
    /**
     * Resolve the path for a theme view file.
     * Implements fallback logic to 'template-1' if the requested file is missing.
     *
     * @param string $view The view filename (e.g., 'index.tpl')
     * @return string The absolute path to the view file.
     */
    function load_theme_view($view) {
        // Sanitize view path to prevent directory traversal
        if (strpos($view, '..') !== false) {
             // Fallback or Exception?
             // Template engine will throw if file not found, so let's just return strict path
             return __DIR__ . '/themes/template-1/' . $view;
        }

        // Determine the active theme
        // Priority: Cookie > DB Setting > Default 'template-1'

        $theme = $_COOKIE['site_theme'] ?? null;

        if (!$theme) {
            // Lazy load settings to avoid overhead if cookie is set?
            // Or just always check DB for default?
            // The memory says: "checking the site_theme cookie, then the default_theme database setting, and finally defaulting to template-1"

            try {
                // We need to ensure Database is connected if we use Models.
                // Assuming this function is called within the app flow where DB is ready.
                $settings = Setting::getAll();
                $theme = $settings['default_theme'] ?? 'template-1';
            } catch (\Exception $e) {
                // If DB fails, fallback to default
                $theme = 'template-1';
            }
        }

        // Sanitize theme name
        $theme = preg_replace('/[^a-zA-Z0-9-_]/', '', $theme);
        if (empty($theme)) $theme = 'template-1';

        $baseDir = __DIR__ . '/themes/';
        $targetPath = $baseDir . $theme . '/' . $view;

        // Check if file exists
        if (!file_exists($targetPath)) {
            // Log missing file for debugging
            error_log("Theme file missing: $targetPath, falling back to template-1");

            $theme = 'template-1';
            $targetPath = $baseDir . $theme . '/' . $view;
        }

        return $targetPath;
    }
}
