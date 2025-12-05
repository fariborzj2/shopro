<?php

use App\Core\Router;
use App\Core\Request;
use App\Core\Plugin\Filter;

use SeoPilot\Enterprise\Injector\AdminInjector;
use SeoPilot\Enterprise\Injector\BufferInjector;


// =============================================================
// 1. Unified URI
// =============================================================
$uri = Request::uri();


// =============================================================
// 2. Admin Settings Routes
// =============================================================
Router::addRoute('GET',  '/admin/seopilot/settings', '\SeoPilot\Enterprise\Controllers\AdminController@index');
Router::addRoute('POST', '/admin/seopilot/settings', '\SeoPilot\Enterprise\Controllers\AdminController@saveSettings');


// =============================================================
// 3. API Routes (Correct Format for Your Router)
// =============================================================
Router::addRoute('POST', '/admin/seopilot/analyze',    '\SeoPilot\Enterprise\Controllers\AnalysisController@analyze');
Router::addRoute('POST', '/admin/seopilot/save',       '\SeoPilot\Enterprise\Controllers\AnalysisController@save');
Router::addRoute('POST', '/admin/seopilot/magic-fix',  '\SeoPilot\Enterprise\Controllers\AnalysisController@magicFix');
Router::addRoute('POST', '/admin/seopilot/suggest',    '\SeoPilot\Enterprise\Controllers\AnalysisController@suggestKeywords');
Router::addRoute('POST', '/admin/seopilot/auto-alt',   '\SeoPilot\Enterprise\Controllers\AnalysisController@autoAlt');


// =============================================================
// 4. Inject Admin Interface
// =============================================================
if (strpos($uri, '/admin') === 0) {
    AdminInjector::handle();
}


// =============================================================
// 5. Sidebar Menu Entry (Fixed URL)
// =============================================================
Filter::add('admin_menu_items', function ($items) {
    $items[] = [
        'label' => 'سئوپایلوت',
        'url' => '/seopilot/settings',
        'icon' => 'search',
        'permission' => 'super_admin'
    ];
    return $items;
});


// =============================================================
// 6. Buffer Injector for Frontend Only
// =============================================================
if (!str_starts_with($uri, '/admin') && !str_starts_with($uri, '/api')) {
    ob_start(['\SeoPilot\Enterprise\Injector\BufferInjector', 'handle']);
}
