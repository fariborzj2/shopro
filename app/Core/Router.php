<?php

namespace App\Core;

use App\Core\ErrorHandler;
use App\Core\Exceptions\RouteNotFoundException;
use Exception;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function load($file)
    {
        $router = new static;
        require $file;
        return $router;
    }

    public function get($uri, $controllerAction)
    {
        $this->routes['GET'][$uri] = $controllerAction;
    }

    public function post($uri, $controllerAction)
    {
        $this->routes['POST'][$uri] = $controllerAction;
    }

    public function dispatch($uri, $requestMethod)
    {
        foreach ($this->routes[$requestMethod] as $route => $action) {
            // First, check for a direct static match.
            if ($route === $uri) {
                list($controller, $method) = explode('@', $action);
                return $this->callAction($controller, $method);
            }

            // If it's not a static match, check for dynamic routes with parameters.
            if (strpos($route, '{') !== false) {
                // Convert the route pattern to a regex.
                // Example: /users/{id}/edit -> #^/users/([^/]+)/edit$#
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route);
                $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';

                if (preg_match($pattern, $uri, $matches)) {
                    list($controller, $method) = explode('@', $action);

                    // Get named parameters from the matches.
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                    return $this->callAction($controller, $method, $params);
                }
            }
        }

        return ErrorHandler::renderHttpError(404, 'صفحه یافت نشد', 'متاسفانه صفحه‌ای که به دنبال آن بودید، وجود ندارد.');
    }

    protected function callAction($controller, $method, $params = [])
    {
        // Support fully qualified class names (starting with \)
        if (strpos($controller, '\\') === 0) {
            $controllerClass = $controller;
        } else {
            $controllerClass = "App\\Controllers\\{$controller}";
        }

        if (!class_exists($controllerClass)) {
            throw new Exception("Controller {$controllerClass} does not exist.");
        }

        $controllerInstance = new $controllerClass;

        if (!method_exists($controllerInstance, $method)) {
            throw new Exception("{$controllerClass} does not respond to the {$method} action.");
        }

        return call_user_func_array([$controllerInstance, $method], $params);
    }
}
