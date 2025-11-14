<?php

// public/index.php

use App\Core\Exceptions\RouteNotFoundException;
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

// Middleware-like check for admin authentication
$uri = Request::uri();
if (strpos($uri, '/admin') === 0 && strpos($uri, '/admin/login') === false) {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /admin/login');
        exit();
    }
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

} catch (RouteNotFoundException $e) {
    http_response_code(404);
    return view('main', 'errors/404', [
        'title' => 'صفحه یافت نشد',
        'message' => $e->getMessage()
    ]);
} catch (Exception $e) {
    // In a real app, you would log the error.
    http_response_code(500);
    return view('main', 'errors/500', [
        'title' => 'خطای سرور',
        'message' => $e->getMessage()
    ]);
}
