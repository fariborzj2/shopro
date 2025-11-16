<?php

namespace App\Core;

class Template
{
    protected $templateDir = __DIR__ . '/../../storefront/templates/';
    protected $vars = [];

    public function __construct($templateDir = null)
    {
        if ($templateDir) {
            $this->templateDir = $templateDir;
        }
    }

    public function assign($key, $value)
    {
        $this->vars[$key] = $value;
    }

    public function render($templateFile, $data = [])
    {
        // Merge assigned variables with data passed to render
        $this->vars = array_merge($this->vars, $data);

        $templatePath = rtrim($this->templateDir, '/') . '/' . $templateFile . '.tpl';

        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found: " . $templatePath);
        }

        extract($this->vars);

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }
}
