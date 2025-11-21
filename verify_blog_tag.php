<?php

define('PROJECT_ROOT', __DIR__);
require PROJECT_ROOT . '/public/index.php';

use App\Models\BlogTag;

try {
    echo "Calling BlogTag::all()...\n";

    if (method_exists('App\Models\BlogTag', 'all')) {
        BlogTag::all();
        echo "Method all() exists and ran successfully.\n";
    } else {
        echo "Method all() DOES NOT exist.\n";

        if (method_exists('App\Models\BlogTag', 'findAll')) {
            echo "Method findAll() exists.\n";
            BlogTag::findAll();
            echo "Called findAll() successfully.\n";
        }
    }

} catch (Error $e) {
    echo "Caught Error: " . $e->getMessage() . "\n";
}
