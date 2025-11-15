<?php

use App\Core\Request;

// app/Core/helpers.php

/**
 * Render a view file and return its content as a string.
 *
 * @param string $view The name of the view file (without .php)
 * @param array $data Data to be extracted for the view
 * @return string
 */
function render($view, $data = [])
{
    extract($data);

    ob_start();
    require __DIR__ . "/../../views/{$view}.php";
    return ob_get_clean();
}

/**
 * Display a view within a layout.
 *
 * @param string $layout The name of the layout file
 * @param string $view The name of the view file to render inside the layout
 * @param array $data Data for both layout and view
 */
function view($layout, $view, $data = [])
{
    $content = render($view, $data);

    echo str_replace(
        ['{{ content }}', '{{ title }}'],
        [$content, $data['title'] ?? 'داشبورد'],
        file_get_contents(__DIR__ . "/../../views/layouts/{$layout}.php")
    );
}

/**
 * Check if the current URI matches a given path.
 *
 * @param string $path
 * @return bool
 */
function is_active($path)
{
    $current_uri = trim(Request::uri(), '/');
    // Special handling for admin dashboard
    if ($path === '/' || $path === '/dashboard') {
        return $current_uri === 'admin' || $current_uri === 'admin/dashboard';
    }
    return str_starts_with($current_uri, 'admin' . rtrim($path, '/'));
}

/**
 * Render a partial view.
 *
 * @param string $partial The name of the partial view file
 * @param array $data Data to be extracted for the partial
 */
function partial($partial, $data = [])
{
    extract($data);
    require __DIR__ . "/../../views/partials/{$partial}.php";
}

/**
 * Generate and store a CSRF token if one doesn't exist.
 *
 * @return string
 */
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify the CSRF token.
 *
 * @return bool
 */
function verify_csrf_token()
{
    $token = null;
    if (isset($_POST['csrf_token'])) {
        $token = $_POST['csrf_token'];
    } elseif (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
    }

    if ($token && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        // Invalidate the token after use for AJAX requests as well for better security
        unset($_SESSION['csrf_token']);
        return true;
    }

    return false;
}

/**
 * Redirect back to the previous page with an error message.
 *
 * @param string $message
 */
function redirect_back_with_error($message)
{
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    // Append error message to the URL
    $url = $referer . (strpos($referer, '?') === false ? '?' : '&') . 'error_msg=' . urlencode($message);
    header("Location: " . $url);
    exit();
}

/**
 * Generate a clean URL for the admin panel.
 *
 * @param string $path
 * @return string
 */
function url($path)
{
    // DEBUG: Check if this function is being called for admin links
    $path = ltrim($path, '/');
    return "/DEBUG_ADMIN/{$path}";
}
