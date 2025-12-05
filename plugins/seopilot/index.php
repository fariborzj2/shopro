<?php

/**
 * SeoPilot Plugin Entry Point
 */

use App\Core\Request;
use SeoPilot\Enterprise\Controllers\AnalysisController;
use SeoPilot\Enterprise\Injector\AdminInjector;
use App\Core\Plugin\Filter;

// 1. Register Routes for API
$uri = Request::uri();

if (strpos($uri, '/admin/seopilot/') === 0) {
    $controller = new AnalysisController();

    if ($uri === '/admin/seopilot/analyze') {
        $controller->analyze();
    } elseif ($uri === '/admin/seopilot/save') {
        $controller->save();
    } elseif ($uri === '/admin/seopilot/magic-fix') {
        $controller->magicFix();
    } elseif ($uri === '/admin/seopilot/suggest') {
        $controller->suggestKeywords();
    } elseif ($uri === '/admin/seopilot/auto-alt') {
        $controller->autoAlt();
    }
}

// 2. Inject Admin Panel Interface
if (strpos($uri, '/admin') === 0) {
    AdminInjector::handle();
}

// 3. Register Sidebar Menu Item
Filter::add('admin_menu_items', function($items) {
    // Check if user has permission (assuming super_admin or a specific permission)
    // Sidebar logic handles permission checks based on the 'permission' key.

    $items[] = [
        'label' => 'سئوپایلوت',
        'url' => '/seopilot/settings',
        'icon' => 'search', // Using a generic icon available in icons partial
        'permission' => 'super_admin' // Restrict to super admin for now
    ];

    return $items;
});
