<?php

namespace App\Core;

class Request
{
    /**
     * Get the current URI from the request.
     *
     * @return string
     */
    public static function uri()
    {
        // Use parse_url to get the path and remove query string
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Ensure the path is not empty and always starts with a slash
        return '/' . trim($uri, '/');
    }

    /**
     * Get the current request method (GET, POST, etc.).
     *
     * @return string
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get the JSON body of the request as an associative array.
     *
     * @return array
     */
    public static function json()
    {
        $json = file_get_contents('php://input');
        if (empty($json)) {
            return [];
        }
        $data = json_decode($json, true);

        // Check for json_decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $data;
    }
}
