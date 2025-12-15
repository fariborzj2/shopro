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
    }

    public static function addAdminMenu($items) {
        $items[] = [
            'label' => 'AI Content Pro',
            'url' => '/admin/ai-content-pro/settings',
            'icon' => 'settings',
            'permission' => 'settings'
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
