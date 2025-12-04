<?php

declare(strict_types=1);

namespace App\Core\Cache;

use App\Core\Cache\Drivers\RedisDriver;
use App\Core\Cache\Drivers\LiteSpeedCacheDriver;
use Exception;

class CacheManager
{
    public function createDriver(array $config): CacheDriverInterface
    {
        $driverName = $config['driver'] ?? 'redis';
        $prefix = $config['prefix'] ?? 'app_v1_';

        switch ($driverName) {
            case 'litespeed':
                return new LiteSpeedCacheDriver();

            case 'redis':
            default:
                // Pass full config to RedisDriver
                return new RedisDriver($prefix, $config);
        }
    }
}
