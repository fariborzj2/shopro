<?php

// public/index.php

use App\Core\Request;
use App\Core\Router;

// Start the session
session_start();

// Middleware-like check for authentication
$is_login_page = (strpos(Request::uri(), 'login') !== false);
if (!isset($_SESSION['admin_id']) && !$is_login_page) {
    header('Location: /login');
    exit();
}

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
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

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
    die($e->getMessage());
}
