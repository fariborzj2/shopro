<?php

namespace SeoPilot\Enterprise\Core;

use App\Core\Database;

class Plugin
{
    public static function activate()
    {
        // Run the installer logic to ensure tables exist
        require_once dirname(__DIR__, 2) . '/install.php';
    }

    public static function deactivate()
    {
        // Optional: clear caches or disable specific flags
        // We typically do NOT drop tables on deactivation to preserve data
    }
}
