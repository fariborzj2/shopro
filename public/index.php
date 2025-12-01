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

// ----------------------------
// Auto Loader 
// ----------------------------
spl_autoload_register(function ($class) {
    // 1. Check for standard App namespace
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // 2. Special handling for Plugins
    // App\Plugins\AiNews\Services\Crawler -> app/Plugins/AiNews/Services/Crawler.php
    if (strpos($class, 'App\\Plugins\\') === 0) {
        $relative_class = substr($class, $len); // Remove 'App\'
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }

    // 3. Standard fallback for other classes
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// ----------------------------
// Error & Exception Handling
// ----------------------------
// Define a global debug mode flag. In a real application, this would come from an environment file.
define('DEBUG_MODE', true);
error_reporting(E_ALL);
ini_set('display_errors', DEBUG_MODE ? 1 : 0);
ini_set('display_startup_errors', DEBUG_MODE ? 1 : 0);

App\Core\ErrorHandler::register();


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

    // Global Permission Guard
    // We only check granular permissions for specific modules here.
    // The AdminsController::checkSuperAdmin handles the 'admins' section.
    // This block ensures that if a user tries to access /admin/products, they have 'products' permission.

    $admin = \App\Models\Admin::find($_SESSION['admin_id']);
    if (!$admin || $admin['status'] !== 'active') {
        // Account deleted or deactivated
        unset($_SESSION['admin_id']);
        header('Location: /admin/login');
        exit();
    }

    // If Super Admin, bypass all checks
    if (!\App\Models\Admin::isSuperAdmin($admin)) {

        // Define URI map to Permissions
        // Key: URI segment, Value: Permission key
        $permissionMap = [
            '/admin/dashboard'      => 'dashboard',
            '/admin/users'          => 'users',
            '/admin/categories'     => 'categories',
            '/admin/products'       => 'products',
            '/admin/media'          => 'media',
            '/admin/custom-fields'  => 'custom_fields',
            '/admin/orders'         => 'orders',
            '/admin/settings'       => 'settings',
            '/admin/blog'           => 'blog',
            '/admin/pages'          => 'pages',
            '/admin/faq'            => 'faq',
            '/admin/reviews'        => 'reviews',
            // 'admins' is handled by checkSuperAdmin in controller, but good to block here too
            '/admin/admins'         => 'SUPER_ADMIN_ONLY'
        ];

        $hasAccess = true;

        foreach ($permissionMap as $prefix => $perm) {
            if (strpos($uri, $prefix) === 0) {
                if ($perm === 'SUPER_ADMIN_ONLY') {
                    $hasAccess = false; // Non-super admins strictly blocked
                } elseif (!\App\Models\Admin::hasPermission($admin, $perm)) {
                    $hasAccess = false;
                }
                break; // Match found, stop checking
            }
        }

        if (!$hasAccess) {
            return \App\Core\ErrorHandler::renderHttpError(403, 'دسترسی غیرمجاز', 'شما مجوز دسترسی به این صفحه را ندارید.');
        }
    }
}

// ----------------------------
// CSRF Protection
// ----------------------------
if (Request::method() === 'POST') {
    if (!verify_csrf_token()) {
        return \App\Core\ErrorHandler::renderHttpError(403, 'نشست نامعتبر', 'نشست شما منقضی شده یا درخواست نامعتبر است. لطفاً صفحه را رفرش کرده و دوباره تلاش کنید.');
    }
}

// ----------------------------
// Router Execution
// ----------------------------
$uri = Request::uri();
$method = Request::method();

Router::load(PROJECT_ROOT . '/app/routes.php')
    ->dispatch($uri, $method);
