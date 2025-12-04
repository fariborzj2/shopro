<?php

require_once __DIR__ . '/public/index.php'; // Bootstrap

use App\Core\Cache;
use App\Core\Cache\CacheManager;

// Example 1: Using the Singleton (Recommended for existing code)
echo "<h3>1. Using Cache Singleton</h3>";
$cache = Cache::getInstance();
$cache->put('test_key', 'Hello Redis', 60);
$value = $cache->get('test_key');
echo "Stored: 'Hello Redis'<br>";
echo "Retrieved: " . var_export($value, true) . "<br>";

// Example 2: Using the Manager directly (For new implementations)
echo "<h3>2. Using CacheManager Directly</h3>";
$manager = new CacheManager();

// Redis Config
$redisConfig = [
    'driver' => 'redis',
    'host' => '127.0.0.1',
    'port' => 6379
];

// LiteSpeed Config
$lsConfig = [
    'driver' => 'litespeed'
];

$redisDriver = $manager->createDriver($redisConfig);
$lsDriver = $manager->createDriver($lsConfig);

// Interact with Redis Driver
$redisDriver->set('manager_key', 'Manager Value', 120);
echo "Redis Driver Get: " . $redisDriver->get('manager_key') . "<br>";

// Interact with LiteSpeed Driver
$lsDriver->set('ls_page_key', 'Page Content', 300);
echo "LiteSpeed Driver Set executed (Headers sent).<br>";
echo "LiteSpeed Driver Get: " . var_export($lsDriver->get('ls_page_key'), true) . " (Expected: null)<br>";

// Clean up
$cache->delete('test_key');
$redisDriver->delete('manager_key');
