<?php

/**
 * Global Cron Entry Point
 * php cron.php
 */

define('PROJECT_ROOT', __DIR__);

// Load all plugins and check for cron.php
$pluginDirs = scandir(PROJECT_ROOT . '/plugins');
foreach ($pluginDirs as $dir) {
    if ($dir === '.' || $dir === '..') continue;

    $cronFile = PROJECT_ROOT . '/plugins/' . $dir . '/cron.php';
    if (file_exists($cronFile)) {
        echo "Running cron for plugin: $dir...\n";
        include $cronFile;
    }
}
