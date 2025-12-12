<?php
// plugins/store/uninstall.php

use App\Core\Database;

$db = Database::getConnection();

// Disable foreign key checks to avoid constraint errors during drop
$db->exec("SET FOREIGN_KEY_CHECKS = 0;");

$tables = [
    'transactions',
    'reviews',
    'category_custom_field',
    'custom_order_fields',
    'orders',
    'products',
    'categories'
];

foreach ($tables as $table) {
    $db->exec("DROP TABLE IF EXISTS `$table`");
}

$db->exec("SET FOREIGN_KEY_CHECKS = 1;");
