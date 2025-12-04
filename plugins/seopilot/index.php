<?php

/**
 * SeoPilot Enterprise Bootstrapper
 *
 * Loaded by App\Core\Plugin\PluginManager
 */

use App\Core\Router;
use App\Core\Plugin\Filter;
use SeoPilot\Enterprise\Injector\BufferInjector;

// 1. Register Routes
// We add routes for the admin settings page.
Router::addRoute('GET', '/admin/seopilot/settings', '\SeoPilot\Enterprise\Controllers\AdminController@index');
Router::addRoute('POST', '/admin/seopilot/settings', '\SeoPilot\Enterprise\Controllers\AdminController@saveSettings');

// 2. Register Sidebar Menu Item
// We use the 'admin_menu_items' filter to inject our menu item.
Filter::add('admin_menu_items', function($items) {
    $items[] = [
        'label' => 'سئوپایلوت',
        'url' => '/seopilot/settings', // url() helper will prepend /admin
        'icon' => 'search',
        'permission' => 'super_admin' // Or a specific permission if defined
    ];
    return $items;
});

// 3. Start Output Buffering for Injection
// This runs on every request where the plugin is active.
// Note: We check if we are NOT in admin panel or API to avoid overhead.
$uri = $_SERVER['REQUEST_URI'] ?? '/';
if (strpos($uri, '/admin') === false && strpos($uri, '/api') === false) {
    ob_start(['\SeoPilot\Enterprise\Injector\BufferInjector', 'handle']);
}
