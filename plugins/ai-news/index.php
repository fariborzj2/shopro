<?php

namespace Plugins\AiNews;

use App\Core\Router;
use App\Core\Plugin\Filter;

// Register Routes
Router::addRoute('GET', '/admin/ai-news/settings', '\Plugins\AiNews\Controllers\AiNewsController@settings');
Router::addRoute('POST', '/admin/ai-news/settings/save', '\Plugins\AiNews\Controllers\AiNewsController@saveSettings');
Router::addRoute('POST', '/admin/ai-news/test-connection', '\Plugins\AiNews\Controllers\AiNewsController@testConnection');
Router::addRoute('GET', '/admin/ai-news/list', '\Plugins\AiNews\Controllers\AiNewsController@list');
Router::addRoute('POST', '/admin/ai-news/fetch', '\Plugins\AiNews\Controllers\AiNewsController@fetch');
Router::addRoute('POST', '/admin/ai-news/approve/{id}', '\Plugins\AiNews\Controllers\AiNewsController@approve');
Router::addRoute('POST', '/admin/ai-news/delete/{id}', '\Plugins\AiNews\Controllers\AiNewsController@delete');
Router::addRoute('POST', '/admin/ai-news/clear-history', '\Plugins\AiNews\Controllers\AiNewsController@clearHistory');
Router::addRoute('POST', '/admin/ai-news/clear-logs', '\Plugins\AiNews\Controllers\AiNewsController@clearLogs');

// Register Menu Item
Filter::add('admin_menu_items', function($items) {
    $items[] = [
        'label' => 'دستیار هوشمند',
        'icon' => 'ai',
        'permission' => 'blog', // Or specific permission 'ai_news'
        'children' => [
            [
                'label' => 'تنظیمات پلاگین',
                'url' => '/ai-news/settings',
            ],
            [
                'label' => 'لیست مطالب هوشمند',
                'url' => '/ai-news/list',
            ],
        ]
    ];
    return $items;
});

class Hooks
{
    public static function activate()
    {
        // Activation logic if needed (e.g. check for existing data)
        // Table creation is handled by install.php which PluginManager runs on install
        // But for activation we might want to ensure they exist too if manually deleted

        $installScript = __DIR__ . '/install.php';
        if (file_exists($installScript)) {
            include $installScript;
        }
    }
}
