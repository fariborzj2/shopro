<?php
// storefront/theme_loader.php

use App\Models\Setting;

function load_theme_view($relativePath) {
    $allowed_themes = ['template-1'];

    // 1. Determine active theme
    // Priority: Cookie > Database Setting > Default 'template-1'
    if (isset($_COOKIE['site_theme']) && in_array($_COOKIE['site_theme'], $allowed_themes)) {
        $theme = $_COOKIE['site_theme'];
    } else {
        // Fetch default from DB
        try {
            $settings = Setting::getAll();
            $default_theme = $settings['default_theme'] ?? 'template-1';

            if (in_array($default_theme, $allowed_themes)) {
                $theme = $default_theme;
            } else {
                $theme = 'template-1';
            }
        } catch (Exception $e) {
            // Fallback in case of DB error
            $theme = 'template-1';
        }
    }

    // 2. Construct path
    // $relativePath e.g., 'index.tpl' or 'blog/index.tpl'
    $baseThemesDir = __DIR__ . '/themes/';
    $targetPath = $baseThemesDir . $theme . '/' . $relativePath;

    // 3. Fallback logic: if file doesn't exist in requested theme, use template-1 (default)
    if (!file_exists($targetPath)) {
        // Optional: Log missing file for debugging
        error_log("Theme file missing: $targetPath, falling back to template-1");
        $theme = 'template-1';
        $targetPath = $baseThemesDir . $theme . '/' . $relativePath;
    }

    // 4. Safety check: ensure fallback exists
    if (!file_exists($targetPath)) {
        // Critical error or show generic 404
        die("View file not found: " . htmlspecialchars($relativePath));
    }

    // 5. Return path
    return $targetPath;
}
