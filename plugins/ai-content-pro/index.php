<?php

use App\Core\Router;
use App\Core\Request;
use App\Core\Plugin\Filter;
use AiContentPro\Controllers\SettingsController;
use AiContentPro\Controllers\ApiController;

// =============================================================
// 1. Admin Routes
// =============================================================
Router::addRoute('GET',  '/admin/ai-content/settings',  '\AiContentPro\Controllers\SettingsController@index');
Router::addRoute('POST', '/admin/ai-content/settings',  '\AiContentPro\Controllers\SettingsController@save');
Router::addRoute('GET',  '/admin/ai-content/logs',      '\AiContentPro\Controllers\SettingsController@logs');

// =============================================================
// 2. API / Queue Routes
// =============================================================
// Trigger queue worker (protected by token or session)
Router::addRoute('POST', '/admin/ai-content/worker/run', '\AiContentPro\Controllers\ApiController@runWorker');
Router::addRoute('GET',  '/admin/ai-content/queue/stats', '\AiContentPro\Controllers\ApiController@queueStats');

// =============================================================
// 3. Admin Menu
// =============================================================
Filter::add('admin_menu_items', function ($items) {
    $items[] = [
        'label' => 'هوش مصنوعی پرو',
        'url' => '/ai-content/settings',
        'icon' => 'cpu', // Assuming feather icons or similar
        'permission' => 'super_admin'
    ];
    return $items;
});
