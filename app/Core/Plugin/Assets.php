<?php

namespace App\Core\Plugin;

class Assets
{
    protected static $scripts = [];
    protected static $styles = [];
    protected static $registeredScripts = [];
    protected static $registeredStyles = [];

    /**
     * Register a script for later use.
     *
     * @param string $handle
     * @param string $src
     * @param array $deps
     * @param string $version
     * @param bool $inFooter
     */
    public static function registerScript($handle, $src, $deps = [], $version = false, $inFooter = true)
    {
        self::$registeredScripts[$handle] = [
            'src' => $src,
            'deps' => $deps,
            'ver' => $version,
            'footer' => $inFooter
        ];
    }

    /**
     * Enqueue a script.
     *
     * @param string $handle
     * @param string|null $src Optional if registered
     * @param array $deps
     */
    public static function addScript($handle, $src = null, $deps = [])
    {
        if (isset(self::$registeredScripts[$handle])) {
            $script = self::$registeredScripts[$handle];
            if ($src) $script['src'] = $src;
            if (!empty($deps)) $script['deps'] = $deps;
            self::$scripts[$handle] = $script;
        } else {
            self::$scripts[$handle] = [
                'src' => $src,
                'deps' => $deps,
                'ver' => false,
                'footer' => true
            ];
        }
    }

    /**
     * Register a style.
     */
    public static function registerStyle($handle, $src, $deps = [], $version = false)
    {
        self::$registeredStyles[$handle] = [
            'src' => $src,
            'deps' => $deps,
            'ver' => $version
        ];
    }

    /**
     * Enqueue a style.
     */
    public static function addStyle($handle, $src = null, $deps = [])
    {
        if (isset(self::$registeredStyles[$handle])) {
            $style = self::$registeredStyles[$handle];
            if ($src) $style['src'] = $src;
            if (!empty($deps)) $style['deps'] = $deps;
            self::$styles[$handle] = $style;
        } else {
            self::$styles[$handle] = [
                'src' => $src,
                'deps' => $deps,
                'ver' => false
            ];
        }
    }

    public static function getScripts($inFooter = false)
    {
        $filtered = [];
        foreach (self::$scripts as $handle => $data) {
            if ($data['footer'] === $inFooter) {
                $filtered[$handle] = $data;
            }
        }

        return self::resolveDependencies($filtered);
    }

    public static function getStyles()
    {
        return self::resolveDependencies(self::$styles);
    }

    /**
     * Simple topological sort for dependencies.
     */
    private static function resolveDependencies($items)
    {
        $resolved = [];
        $visited = [];
        $visiting = [];

        $visit = function($handle) use (&$visit, &$resolved, &$visited, &$visiting, $items) {
            if (isset($visited[$handle])) return;
            if (isset($visiting[$handle])) {
                // Circular dependency detected, skip but log warning in real app
                return;
            }

            $visiting[$handle] = true;

            // Check if item exists in the list being resolved, or in registered items
            $item = $items[$handle] ?? (self::$registeredScripts[$handle] ?? (self::$registeredStyles[$handle] ?? null));

            if ($item) {
                foreach ($item['deps'] as $dep) {
                    $visit($dep);
                }

                $resolved[$handle] = $item;
            }

            $visiting[$handle] = false;
            $visited[$handle] = true;
        };

        foreach (array_keys($items) as $handle) {
            $visit($handle);
        }

        return $resolved;
    }

    public static function renderStyles()
    {
        foreach (self::getStyles() as $handle => $style) {
            echo '<link rel="stylesheet" id="' . htmlspecialchars($handle) . '" href="' . htmlspecialchars($style['src']) . '">' . PHP_EOL;
        }
    }

    public static function renderScripts($inFooter = false)
    {
        $scripts = self::getScripts($inFooter);
        foreach ($scripts as $handle => $script) {
            echo '<script id="' . htmlspecialchars($handle) . '" src="' . htmlspecialchars($script['src']) . '"></script>' . PHP_EOL;
        }
    }
}
