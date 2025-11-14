<?php

namespace App\Core;

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
        // First, check for a direct match for static routes.
        if (array_key_exists($uri, $this->routes[$requestMethod])) {
            list($controller, $method) = explode('@', $this->routes[$requestMethod][$uri]);
            return $this->callAction($controller, $method);
        }

        // If not found, check for dynamic routes with parameters.
        foreach ($this->routes[$requestMethod] as $route => $action) {
            // Skip routes that don't have placeholders to avoid unnecessary regex.
            if (strpos($route, '{') === false) {
                continue;
            }

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

        throw new RouteNotFoundException('No route defined for this URI: ' . htmlspecialchars($uri));
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
