<?php

/**
 * Render a store plugin view within the global admin layout.
 *
 * @param string $layout The name of the global layout file (e.g., 'main').
 * @param string $view The name of the plugin view file (e.g., 'categories/index').
 * @param array $data Data to be passed to the view and layout.
 */
function store_view($layout, $view, $data = [])
{
    // 1. Render the plugin view content
    // The view path passed is usually relative to 'plugins/store/views/admin/'
    // e.g. 'categories/index' maps to 'plugins/store/views/admin/categories/index.php'
    
    // Determine the path to the plugin's view directory
    // __DIR__ is plugins/store/ (where this helper is located)
    $pluginViewBase = __DIR__ . '/views/admin'; 
    $viewPath = $pluginViewBase . '/' . $view . '.php';

    if (!file_exists($viewPath)) {
        // Fallback: try looking in non-admin views or handle error
        // For now, fail gracefully or show error
        $fallbackPath = __DIR__ . '/views/' . $view . '.php';
        if (file_exists($fallbackPath)) {
            $viewPath = $fallbackPath;
        } else {
            // Error handling matching the core view() style (which echoes a comment or exception)
            // But since this is a critical failure, let's throw an exception or die to be visible
            throw new \Exception("Store Plugin View not found: " . $view . " (searched in $pluginViewBase)");
        }
    }

    // Extract data for the view
    extract($data);

    // Capture view content
    ob_start();
    require $viewPath;
    $content = ob_get_clean();

    // 2. Render the global layout with this content
    // We add the rendered content to the data array
    $data['content'] = $content;
    
    // Re-extract data including 'content' for the layout to use
    extract($data);

    // Layout path (global core layouts)
    // Using PROJECT_ROOT which is defined in public/index.php
    $layoutPath = PROJECT_ROOT . "/views/layouts/{$layout}.php";

    if (file_exists($layoutPath)) {
        // Render layout
        // Note: view() helper in core uses ob_start/require/echo ob_get_clean logic.
        // We do the same to isolate scope somewhat and ensure full execution.
        ob_start();
        require $layoutPath;
        echo ob_get_clean();
    } else {
        // Fallback if layout is missing: just show content
        echo $content;
    }
}
