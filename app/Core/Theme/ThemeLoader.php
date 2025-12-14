<?php

namespace App\Core\Theme;

use App\Models\Setting;

class ThemeLoader
{
    public static function resolve($view)
    {
        if (strpos($view, '..') !== false) {
             return PROJECT_ROOT . '/views/site/themes/template-1/' . $view;
        }

        $theme = self::getActiveTheme();

        $coreBaseDir = PROJECT_ROOT . '/views/site/themes/';
        $corePath = $coreBaseDir . $theme . '/' . $view;

        if (file_exists($corePath)) {
            return $corePath;
        }

        // Fallback to default
        $theme = 'template-1';
        $corePath = $coreBaseDir . $theme . '/' . $view;
        if (file_exists($corePath)) {
             return $corePath;
        }

        return $corePath;
    }

    private static function getActiveTheme()
    {
        $theme = $_COOKIE['site_theme'] ?? null;
        if (!$theme) {
            try {
                $settings = Setting::getAll();
                $theme = $settings['default_theme'] ?? 'template-1';
            } catch (\Exception $e) {
                $theme = 'template-1';
            }
        }
        return preg_replace('/[^a-zA-Z0-9-_]/', '', $theme) ?: 'template-1';
    }
}
