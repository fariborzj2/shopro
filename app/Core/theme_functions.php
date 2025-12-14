<?php

use App\Core\Theme\ThemeLoader;

if (!function_exists('load_theme_view')) {
    function load_theme_view($view) {
        return ThemeLoader::resolve($view);
    }
}
