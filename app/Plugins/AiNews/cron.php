<?php

// app/Plugins/AiNews/cron.php

// Define PROJECT_ROOT manually since we are running from CLI
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));

// Load Database Core
require_once PROJECT_ROOT . '/app/Core/Database.php';
require_once PROJECT_ROOT . '/app/Core/helpers.php';
require_once PROJECT_ROOT . '/app/Core/jdf.php';

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = PROJECT_ROOT . '/app/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Run Crawler
echo "Starting AI News Crawler...\n";

try {
    $crawler = new \App\Plugins\AiNews\Services\Crawler();
    $result = $crawler->run(false); // Manual = false (check schedule)

    echo "Result: " . json_encode($result) . "\n";

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
