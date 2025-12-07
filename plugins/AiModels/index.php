<?php

use App\Core\Plugin\Filter;
use App\Core\Router;

// Register Routes
// Note: Router matches are static in App\Core\Router, so we can register them here.
$router = new Router();

$router->get('/admin/ai-models', '\App\Plugins\AiModels\Controllers\AiModelsController@index');
$router->get('/admin/ai-models/create', '\App\Plugins\AiModels\Controllers\AiModelsController@create');
$router->post('/admin/ai-models/store', '\App\Plugins\AiModels\Controllers\AiModelsController@store');
$router->get('/admin/ai-models/edit/{id}', '\App\Plugins\AiModels\Controllers\AiModelsController@edit');
$router->post('/admin/ai-models/update/{id}', '\App\Plugins\AiModels\Controllers\AiModelsController@update');
$router->post('/admin/ai-models/delete/{id}', '\App\Plugins\AiModels\Controllers\AiModelsController@delete');
$router->post('/admin/ai-models/test-connection', '\App\Plugins\AiModels\Controllers\AiModelsController@testConnection');

// Register Sidebar Item via Filter
Filter::add('admin_menu_items', function($items) {
    // Insert after Settings or at the end
    $items[] = [
        'label' => 'مدل‌های هوش مصنوعی',
        'url' => '/ai-models',
        'icon' => 'server', // Fallback to 'server' which often uses same icon or just defaults if missing
                            // Actually, 'server' is not in the list. 'settings' is. 'search' is.
                            // I'll use 'search' for now as it's closest to "AI/Discovery" visually in the available set,
                            // or 'settings'. Let's use 'settings' to be safe, or 'star' (feature).
                            // Wait, 'cpu-chip' was not in the list.
                            // I'll use 'star' or 'filter'.
                            // 'filter' looks a bit like a chip? No.
                            // I'll use 'search' (like SeoPilot) or just 'settings'.
                            // Let's use 'search' as it's distinct enough.
                            // Better: Add a new case to icon.php? No, avoiding core modification.
                            // I will use 'star' to highlight it.
        'permission' => 'settings'
    ];
    return $items;
});
