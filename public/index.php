<?php

// public/index.php

use App\Core\Exceptions\RouteNotFoundException;
use App\Core\Request;
use App\Core\Router;

// Start the session
session_start();

// Define a constant for the project root directory
define('PROJECT_ROOT', dirname(__DIR__));

// Add the storefront templates directory to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . PROJECT_ROOT . '/storefront/templates');

// Set the default timezone to Tehran
date_default_timezone_set('Asia/Tehran');

// Show all errors for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ----------------------------
// Auto Loader 
// ----------------------------
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

// ----------------------------
// Load global helpers (first)
// ----------------------------
require PROJECT_ROOT . '/app/Core/helpers.php';

// ----------------------------
// Load JDF globally (only once)
// ----------------------------
require PROJECT_ROOT . '/app/Core/jdf.php';

// Double-check JDF loaded correctly
if (!function_exists('jdate')) {
    die("Fatal: jdf.php loaded but jdate() function not found.");
}

// ----------------------------
// Admin Auth Middleware
// ----------------------------
$uri = Request::uri();

if (strpos($uri, '/admin') === 0 && strpos($uri, '/admin/login') === false) {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /admin/login');
        exit();
    }
}

// ----------------------------
// CSRF Protection
// ----------------------------
if (Request::method() === 'POST') {
    if (!verify_csrf_token()) {
        http_response_code(403);
        echo "<h1>403 Forbidden</h1><p>Invalid CSRF token.</p>";
        exit();
    }
}

// ----------------------------
// Router Execution
// ----------------------------
try {
    $uri = Request::uri();
    $method = Request::method();

    Router::load(PROJECT_ROOT . '/app/routes.php')
        ->dispatch($uri, $method);

} catch (RouteNotFoundException $e) {

    http_response_code(404);
    return view('error', 'errors/404', [
        'title' => '404 - صفحه یافت نشد',
        'message' => $e->getMessage()
    ]);

} catch (Exception $e) {

    http_response_code(500);
    return view('error', 'errors/500', [
        'title' => '500 - خطای سرور',
        'message' => $e->getMessage()
    ]);
}
