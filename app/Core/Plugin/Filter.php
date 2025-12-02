<?php

namespace App\Core\Plugin;

class Filter
{
    protected static $filters = [];

    /**
     * Add a filter.
     *
     * @param string $tag
     * @param callable $callback
     * @param int $priority
     */
    public static function add(string $tag, callable $callback, int $priority = 10)
    {
        self::$filters[$tag][$priority][] = $callback;
    }

    /**
     * Apply filters to a value.
     *
     * @param string $tag
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    public static function apply(string $tag, $value, ...$args)
    {
        if (!isset(self::$filters[$tag])) {
            return $value;
        }

        ksort(self::$filters[$tag]);

        foreach (self::$filters[$tag] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                if (is_callable($callback)) {
                    // The first argument to the callback is always the value being filtered
                    $value = call_user_func_array($callback, array_merge([$value], $args));
                }
            }
        }

        return $value;
    }
}
