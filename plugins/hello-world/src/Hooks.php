<?php

namespace Plugins\HelloWorld;

class Hooks
{
    public static function activate()
    {
        error_log('Hello World Plugin Activated!');

        // Insert a sample message into the database
        $db = \App\Core\Database::getConnection();
        $stmt = $db->prepare("INSERT INTO hello_messages (message) VALUES (?)");
        $stmt->execute(['Hello from the Plugin Activation Hook!']);
    }

    public static function boot()
    {
        error_log('Hello World Plugin Booted!');
    }
}
