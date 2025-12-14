<?php

namespace App\Core;

class Hook
{
    protected static $listeners = [];

    public static function add($hook, $callback)
    {
        if (!isset(self::$listeners[$hook])) {
            self::$listeners[$hook] = [];
        }
        self::$listeners[$hook][] = $callback;
    }

    public static function trigger($hook, ...$args)
    {
        if (isset(self::$listeners[$hook])) {
            foreach (self::$listeners[$hook] as $callback) {
                call_user_func_array($callback, $args);
            }
        }
    }

    public static function has($hook)
    {
        return !empty(self::$listeners[$hook]);
    }
}
