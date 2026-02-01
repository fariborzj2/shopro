<?php

namespace AiContentPro;

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

        // Add Admin Scripts
        if (class_exists('App\Core\Plugin\Assets')) {
            \App\Core\Plugin\Assets::addScript('ai-cp-admin', '/plugins/ai-content-pro/admin.js');
        }
    }

    public static function addAdminMenu($items) {
        $items[] = [
            'label' => 'هوش مصنوعی (Pro)',
            'icon' => 'ai', // Make sure to handle this icon or use a fallback
            'permission' => 'settings',
            'children' => [
                [
                    'label' => 'داشبورد هوشمند',
                    'url' => '/ai-content-pro',
                ],
                [
                    'label' => 'تولید محتوا',
                    'url' => '/ai-content-pro/generator',
                ],
                [
                    'label' => 'تقویم محتوایی',
                    'url' => '/ai-content-pro/calendar',
                ],
                [
                    'label' => 'تنظیمات',
                    'url' => '/ai-content-pro/settings',
                ],
            ]
        ];
        return $items;
    }

    private static function registerRoutes() {
        $routesPath = __DIR__ . '/../../routes.php';
        if (file_exists($routesPath)) {
            require_once $routesPath;
        }
    }

    private static function setDefaultSettings() {
        $defaults = [
            'gemini_api_key' => '',
            'enable_content_gen' => '0',
            'enable_faq_gen' => '0',
            'enable_image_gen' => '0',
            'enable_internal_links' => '0',
            'enable_seo' => '0',
            'enable_comments' => '0',
            'enable_calendar' => '0',
            'model_content' => 'gemini-1.5-flash',
            'max_tokens_content' => '2000',
            'language' => 'fa',
            'seo_title_length' => '60',
            'seo_desc_length' => '160',
            'queue_retry_limit' => '3',
        ];

        $db = Database::getConnection();
        foreach ($defaults as $key => $value) {
            $stmt = $db->prepare("INSERT IGNORE INTO ai_cp_settings (`key`, `value`) VALUES (?, ?)");
            $stmt->execute([$key, $value]);
        }
    }
}
