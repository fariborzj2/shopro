<?php

namespace AiContentPro;

use App\Core\Database;
use AiContentPro\Services\GeminiService;

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
    }

    private static function registerRoutes() {
        // Explicitly load the routes file
        $routesPath = __DIR__ . '/../../routes.php';
        if (file_exists($routesPath)) {
            require_once $routesPath;
        }
    }

    private static function setDefaultSettings() {
        $defaults = [
            'gemini_api_key' => '',
            'enable_content_gen' => '0',
            'enable_seo' => '0',
            'enable_comments' => '0',
            'enable_calendar' => '0',
            'model_content' => 'gemini-2.5-flash',
            'max_tokens_content' => '2000',
            'language' => 'fa',
            // Granular defaults
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
