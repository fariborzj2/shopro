<?php

namespace Plugins\HelloWorld;

class Hooks
{
    public static function activate()
    {
        error_log('Hello World Plugin Activated!');
    }

    public static function boot()
    {
        error_log('Hello World Plugin Booted!');
    }
}
