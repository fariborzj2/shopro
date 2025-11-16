<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/Core/Database.php';
require __DIR__ . '/app/Models/Category.php';

$config = require 'config.php';
\App\Core\Database::setConfig($config['database']);

\App\Models\Category::create([
    'title' => 'دسته بندی تستی',
    'name_fa' => 'دسته بندی تستی',
    'name_en' => 'Test Category',
    'slug' => 'test-category',
    'parent_id' => null,
    'status' => 'active'
]);

echo "Test category created successfully.\n";
