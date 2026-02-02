<?php

namespace AiNews\Controllers;

class BaseController {
    protected function view($view, $data = [], $title = 'AI News Automation') {
        extract($data);

        ob_start();
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "View not found: " . $viewPath;
        }
        $content = ob_get_clean();

        if (file_exists(PROJECT_ROOT . '/views/layouts/main.php')) {
            require PROJECT_ROOT . '/views/layouts/main.php';
        } else {
            echo $content;
        }
    }
}
