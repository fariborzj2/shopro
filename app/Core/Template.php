<?php

namespace App\Core;

class Template
{
    protected $templateDir = null;
    protected $vars = [];

    public function __construct($templateDir = null)
    {
        $this->templateDir = $templateDir;
    }

    public function assign($key, $value)
    {
        $this->vars[$key] = $value;
    }

    public function render($templateFile, $data = [])
    {
        // Merge assigned variables with data passed to render
        $this->vars = array_merge($this->vars, $data);

        // Path resolution logic
        $templatePath = '';

        // 1. If a directory was provided to constructor, use it (standard behavior)
        // But only if it's not the legacy invalid path
        $isLegacyPath = ($this->templateDir && strpos($this->templateDir, 'storefront/templates') !== false && !is_dir($this->templateDir));

        if ($this->templateDir && !$isLegacyPath) {
             $templatePath = rtrim($this->templateDir, '/') . '/' . $templateFile . '.tpl';
        }
        // 2. If no directory provided (or legacy invalid path), try to use the theme loader
        elseif (function_exists('load_theme_view')) {
            $templatePath = load_theme_view($templateFile . '.tpl');
        }
        // 3. Fallback to default if no other option
        else {
             // If we are here, we probably have no valid directory.
             // We can try a default relative path or fail.
             // Original default was __DIR__ . '/../../storefront/templates/' which is invalid now.
             // Let's assume the user might have set it or it will fail.
             if ($this->templateDir) {
                 $templatePath = rtrim($this->templateDir, '/') . '/' . $templateFile . '.tpl';
             }
        }

        if (empty($templatePath) || !file_exists($templatePath)) {
             // Try one last desperate attempt if we have a legacy path string but file_exists failed
             if ($this->templateDir) {
                 $templatePath = rtrim($this->templateDir, '/') . '/' . $templateFile . '.tpl';
             }
        }

        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found: " . ($templatePath ?: $templateFile));
        }

        extract($this->vars);

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }
}
