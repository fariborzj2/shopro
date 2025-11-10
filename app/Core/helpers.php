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
    return Request::uri() === trim($path, '/');
}
