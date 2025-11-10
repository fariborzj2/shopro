<?php

// public/index.php

use App\Core\Request;
use App\Core\Router;

// Show all errors for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// A simple autoloader
spl_autoload_register(function ($class) {
    file_put_contents('autoload.log', "Attempting to load class: {$class}\n", FILE_APPEND);

    // Project-specific namespace prefix
    $prefix = 'App\\';

    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/../app/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
        file_put_contents('autoload.log', "Successfully loaded class: {$class}\n", FILE_APPEND);
    } else {
        file_put_contents('autoload.log', "File not found for class: {$class} at path: {$file}\n", FILE_APPEND);
    }
});

// Load helpers
require __DIR__ . '/../app/Core/helpers.php';


try {
    $uri = Request::uri();
    $method = Request::method();

    Router::load(__DIR__ . '/../app/routes.php')
        ->dispatch($uri, $method);

} catch (Exception $e) {
    die($e->getMessage());
}
