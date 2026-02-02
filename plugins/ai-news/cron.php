<?php

/**
 * AI News Automation - Cron Script
 * Usage: php plugins/ai-news/cron.php
 */

define('PROJECT_ROOT', dirname(dirname(__DIR__)));

// 1. Basic Autoloader for Plugin and Core
require_once PROJECT_ROOT . '/app/Core/Database.php';

spl_autoload_register(function ($class) {
    // Handle App namespace
    if (strpos($class, 'App\\') === 0) {
        $path = PROJECT_ROOT . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (file_exists($path)) require_once $path;
    }
    // Handle AiNews namespace
    if (strpos($class, 'AiNews\\') === 0) {
        $path = PROJECT_ROOT . '/plugins/ai-news/src/' . str_replace('\\', '/', substr($class, 7)) . '.php';
        if (file_exists($path)) require_once $path;
    }
    // Handle AiContentPro namespace
    if (strpos($class, 'AiContentPro\\') === 0) {
        $path = PROJECT_ROOT . '/plugins/ai-content-pro/src/' . str_replace('\\', '/', substr($class, 13)) . '.php';
        if (file_exists($path)) require_once $path;
    }
});

use AiNews\Services\Crawler;
use AiNews\Services\Processor;
use AiNews\Models\Setting;
use AiNews\Models\AiNewsLog;

try {
    // Check if automation is enabled
    if (Setting::get('ai_news_status') !== 'active') {
        exit("AI News Automation is disabled.\n");
    }

    // Check Interval
    $lastRun = Setting::get('ai_news_last_run');
    $interval = (int) Setting::get('ai_news_interval', 360);

    if ($lastRun && (time() - strtotime($lastRun)) < ($interval * 60)) {
        exit("Interval not reached. Last run: $lastRun\n");
    }

    AiNewsLog::info("شروع خودکار فرآیند کرال توسط سیستم (Cron)");

    $crawler = new Crawler();
    $found = $crawler->run();

    $processor = new Processor();
    $processed = $processor->processPending();

    echo "Finished. Found: $found, Processed: $processed\n";

} catch (\Exception $e) {
    AiNewsLog::error("Cron Job Exception: " . $e->getMessage());
    echo "Error: " . $e->getMessage() . "\n";
}
