<?php

namespace App\Core\Plugin;

class Hook
{
    protected static $listeners = [];

    /**
     * Add a hook listener.
     *
     * @param string $event
     * @param callable $callback
     * @param int $priority
     */
    public static function add(string $event, callable $callback, int $priority = 10)
    {
        self::$listeners[$event][$priority][] = $callback;
    }

    /**
     * Fire a hook event.
     *
     * @param string $event
     * @param mixed ...$args
     */
    public static function fire(string $event, ...$args)
    {
        if (!isset(self::$listeners[$event])) {
            return;
        }

        // Sort by priority (asc)
        ksort(self::$listeners[$event]);

        foreach (self::$listeners[$event] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                if (is_callable($callback)) {
                    call_user_func_array($callback, $args);
                }
            }
        }
    }

    /**
     * Remove a hook listener.
     *
     * @param string $event
     */
    public static function remove(string $event)
    {
        if (isset(self::$listeners[$event])) {
            unset(self::$listeners[$event]);
        }
    }
}
