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
    // First, render the main content view to a variable
    $content = render($view, $data);

    // Now, render the main layout, making the content and other data available to it.
    // By using require within an output buffer, we ensure all PHP in the layout file is executed.
    $data['content'] = $content;
    extract($data);

    // Use the PROJECT_ROOT constant for a reliable path
    $layout_path = PROJECT_ROOT . "/views/layouts/{$layout}.php";

    if (file_exists($layout_path)) {
        ob_start();
        require $layout_path;
        echo ob_get_clean();
    } else {
        // Fallback if layout is not found
        echo $content;
    }
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
    $partial_path = PROJECT_ROOT . "/views/partials/{$partial}.php";
    if (file_exists($partial_path)) {
        require $partial_path;
    } else {
        // You could log an error here or show a placeholder
        echo "<!-- Partial view not found: {$partial} -->";
    }
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
 * Generate a hidden input field with the CSRF token.
 *
 */
function csrf_field()
{
    echo '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
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
        // Token is valid. For better security, regenerate the token immediately
        // after successful validation. This prevents token reuse while ensuring
        // the next request will have a valid token available.
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
    // Ensure the path starts with a slash and is prefixed with /admin for admin panel links.
    $path = ltrim($path, '/');
    return "/admin/{$path}";
}

/**
 * Generate a URL for an asset.
 *
 * @param string $path
 * @return string
 */
function asset($path)
{
    // A simple asset helper that just ensures the path is absolute from the root.
    // In a more complex setup, this might point to a CDN or add versioning.
    return '/' . ltrim($path, '/');
}

/**
 * Build hierarchical category options for a select dropdown.
 *
 * @param array $categories Array of category objects/arrays.
 * @param int|null $parentId The ID of the parent to start from.
 * @param int $level The current depth level for indentation.
 * @param int|null $selectedId The ID of the currently selected category.
 * @param int|null $currentCategoryId The ID of the category being edited (to exclude it and its children).
 */
function build_category_tree_options(array $categories, $parentId = null, $level = 0, $selectedId = null, $currentCategoryId = null)
{
    $html = '';
    foreach ($categories as $category) {
        // Ensure category is an object for consistent access
        $category = (object)$category;

        if ($category->parent_id == $parentId) {
            // Exclude the category being edited and its descendants
            if ($currentCategoryId !== null && $category->id == $currentCategoryId) {
                continue;
            }

            $isSelected = ($selectedId !== null && $selectedId == $category->id) ? 'selected' : '';
            $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);

            $html .= "<option value=\"{$category->id}\" {$isSelected}>";
            $html .= $indent . htmlspecialchars($category->name_fa);
            $html .= "</option>";

            // Recursively find children, excluding descendants of the current category
            $childHtml = build_category_tree_options($categories, $category->id, $level + 1, $selectedId, $currentCategoryId);
            $html .= $childHtml;
        }
    }
    return $html;
}
