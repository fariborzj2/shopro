<?php

namespace AiNews;

use App\Core\Database;
use App\Core\Plugin\Filter;

class Hooks {
    public static function activate() {
        self::setDefaultSettings();
    }

    public static function deactivate() {
        // Runs on plugin deactivation
    }

    public static function boot() {
        // Register Routes
        self::registerRoutes();

        // Register Admin Menu
        if (class_exists('App\Core\Plugin\Filter')) {
            Filter::add('admin_menu_items', [self::class, 'addAdminMenu']);
        }
    }

    public static function addAdminMenu($items) {
        $items[] = [
            'label' => 'اتوماسیون محتوا (AI News)',
            'icon' => 'ai',
            'permission' => 'settings',
            'children' => [
                [
                    'label' => 'داشبورد اتوماسیون',
                    'url' => '/ai-news',
                ],
                [
                    'label' => 'منابع محتوا (Sources)',
                    'url' => '/ai-news/sources',
                ],
                [
                    'label' => 'تنظیمات خودکار',
                    'url' => '/ai-news/settings',
                ],
            ]
        ];
        return $items;
    }

    private static function registerRoutes() {
        $routesPath = __DIR__ . '/../routes.php';
        if (file_exists($routesPath)) {
            require_once $routesPath;
        }
    }

    private static function setDefaultSettings() {
        $defaults = [
            'ai_news_interval' => '360', // minutes (6 hours)
            'ai_news_limit_per_run' => '5',
            'ai_news_target_category' => '1',
            'ai_news_status' => 'inactive',
            'ai_news_auto_publish' => '0',
            'ai_news_model' => 'gemini-1.5-flash',
            'ai_news_last_run' => '',
        ];

        $db = Database::getConnection();
        foreach ($defaults as $key => $value) {
            $stmt = $db->prepare("INSERT IGNORE INTO ai_news_settings (`setting_key`, `setting_value`) VALUES (?, ?)");
            $stmt->execute([$key, $value]);
        }
    }
}
