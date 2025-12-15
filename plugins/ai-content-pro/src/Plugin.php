<?php

namespace AiContentPro;

class Plugin
{
    public static function activate()
    {
        // Additional activation logic if needed
        // Tables are created by install.php which is run by PluginManager::install
        // But we can ensure default settings are loaded here if needed
    }

    public static function deactivate()
    {
        // Deactivation logic (e.g. clear cache, stop queues)
    }
}
