<?php

// public/index.php

use App\Core\Request;
use App\Core\Router;

// Start the session
session_start();

// Set the default timezone to Tehran
date_default_timezone_set('Asia/Tehran');

// Show all errors for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// A simple autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
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

// Middleware-like check for authentication
// This logic must come *after* the autoloader, as it uses the Request class.
$is_login_page = (strpos(Request::uri(), 'login') !== false);
if (!isset($_SESSION['admin_id']) && !$is_login_page) {
    header('Location: /login');
    exit();
}

// Load helpers
require __DIR__ . '/../app/Core/helpers.php';
require __DIR__ . '/../app/Core/jdf.php';

// CSRF Protection for POST requests
if (Request::method() === 'POST') {
    if (!verify_csrf_token()) {
        // Handle invalid token.
        http_response_code(403);
        echo "<h1>403 Forbidden</h1><p>Invalid CSRF token.</p>";
        exit();
    }
}


try {
    $uri = Request::uri();
    $method = Request::method();

    Router::load(__DIR__ . '/../app/routes.php')
        ->dispatch($uri, $method);

} catch (Exception $e) {
    // In a real app, you would log the error.
    http_response_code(500);
    return view('main', 'errors/500', [
        'title' => 'خطای سرور',
        'message' => $e->getMessage()
    ]);
}
