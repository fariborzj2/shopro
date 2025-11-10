<?php

// public/index.php

use App\Core\Request;
use App\Core\Router;

// Show all errors for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// A simple autoloader
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'App\\';

    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/../app/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
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
    // For now, just die with the error message
    die($e->getMessage());
}
