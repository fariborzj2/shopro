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
        // We use trim to remove slashes from the beginning and end of the URI
        return trim(
            // We use parse_url to handle query strings
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            '/'
        );
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
}
