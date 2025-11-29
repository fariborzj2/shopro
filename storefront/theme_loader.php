<?php
// storefront/theme_loader.php

function load_theme_view($relativePath) {
    // 1. Determine active theme from cookie
    $theme = $_COOKIE['site_theme'] ?? 'template-1';
    $allowed_themes = ['template-1', 'template-2'];

    if (!in_array($theme, $allowed_themes)) {
        $theme = 'template-1';
    }

    // 2. Construct path
    // $relativePath e.g., 'index.tpl' or 'blog/index.tpl'
    $baseThemesDir = __DIR__ . '/themes/';
    $targetPath = $baseThemesDir . $theme . '/' . $relativePath;

    // 3. Fallback logic: if file doesn't exist in requested theme, use template-1 (default)
    if (!file_exists($targetPath)) {
        // Optional: Log missing file for debugging
        // error_log("Theme file missing: $targetPath, falling back to template-1");
        $theme = 'template-1';
        $targetPath = $baseThemesDir . $theme . '/' . $relativePath;
    }

    // 4. Safety check: ensure fallback exists
    if (!file_exists($targetPath)) {
        // Critical error or show generic 404
        die("View file not found: " . htmlspecialchars($relativePath));
    }

    // 5. Include the file
    // Note: We use include inside this function scope.
    // Variables from the calling scope (Controller) are NOT automatically available here
    // unless we use 'extract($GLOBALS)' or similar, BUT `include` inside a function
    // does not inherit variables from the caller of the function.
    //
    // HOWEVER, the Proxy files will simply `include` this loader?
    // No, better to have a helper function that returns the PATH, and the Proxy file includes it.
    // That way, the inclusion happens in the Proxy file's scope, preserving variable access ($data).

    return $targetPath;
}
