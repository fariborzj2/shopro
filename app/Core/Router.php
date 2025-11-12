<?php

namespace App\Core;

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
        // Handle the root URI case explicitly
        if ($uri === '' && isset($this->routes[$requestMethod]['/'])) {
             list($controller, $method) = explode('@', $this->routes[$requestMethod]['/']);
             return $this->callAction($controller, $method);
        }

        foreach ($this->routes[$requestMethod] as $route => $action) {
            // Escape forward slashes for regex
            $route = str_replace('/', '\/', $route);

            // Convert route with params like {id} to a regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                list($controller, $method) = explode('@', $action);
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->callAction($controller, $method, $params);
            }
        }

        throw new Exception('No route defined for this URI.');
    }

    protected function callAction($controller, $method, $params = [])
    {
        $controllerClass = "App\\Controllers\\{$controller}";

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
