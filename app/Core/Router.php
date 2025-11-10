<?php

namespace App\Core;

use Exception;

class Router
{
    protected $routes = [];

    /**
     * Add a GET route
     *
     * @param string $uri
     * @param string $controllerAction
     */
    public function get($uri, $controllerAction)
    {
        $this->routes['GET'][$uri] = $controllerAction;
    }

    /**
     * Add a POST route
     *
     * @param string $uri
     * @param string $controllerAction
     */
    public function post($uri, $controllerAction)
    {
        $this->routes['POST'][$uri] = $controllerAction;
    }

    /**
     * Load the router file
     *
     * @param string $file
     * @return Router
     */
    public static function load($file)
    {
        $router = new static;
        require $file;
        return $router;
    }

    /**
     * Dispatch the request to the appropriate controller and action.
     *
     * @param string $uri
     * @param string $requestMethod
     */
    public function dispatch($uri, $requestMethod)
    {
        if (array_key_exists($uri, $this->routes[$requestMethod])) {
            // Explode the controller and method string, e.g., 'PagesController@home'
            list($controller, $method) = explode('@', $this->routes[$requestMethod][$uri]);

            // Call the controller action
            return $this->callAction($controller, $method);
        }

        throw new Exception('No route defined for this URI.');
    }

    /**
     * Load the controller and call the method.
     *
     * @param string $controller
     * @param string $method
     */
    protected function callAction($controller, $method)
    {
        $controllerClass = "App\\Controllers\\{$controller}";

        if (!class_exists($controllerClass)) {
            throw new Exception("Controller {$controllerClass} does not exist.");
        }

        $controllerInstance = new $controllerClass;

        if (!method_exists($controllerInstance, $method)) {
            throw new Exception(
                "{$controllerClass} does not respond to the {$method} action."
            );
        }

        return $controllerInstance->$method();
    }
}
