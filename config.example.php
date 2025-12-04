<?php

// config.example.php

// Database configuration example
return [
    'database' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'database_name',
        'user' => 'database_user',
        'password' => 'database_password',
        'charset' => 'utf8mb4'
    ],
    // Cache configuration
    'cache' => [
        'driver' => 'redis', // 'redis' or 'litespeed'
        'prefix' => 'app_v1_',
        'redis' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => null,
            'db' => 0,
        ],
        'debug' => false
    ]
];
