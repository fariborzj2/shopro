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

    public function render($templateFile)
    {
        $templatePath = $this->templateDir . $templateFile;

        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found: " . $templatePath);
        }

        extract($this->vars);

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }
}
