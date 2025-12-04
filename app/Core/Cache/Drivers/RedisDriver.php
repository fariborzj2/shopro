<?php

declare(strict_types=1);

namespace App\Core\Cache\Drivers;

use App\Core\Cache\CacheDriverInterface;
use Throwable;

class RedisDriver implements CacheDriverInterface
{
    protected ?\Redis $redis = null;
    protected string $prefix;
    protected bool $debug = false;

    public function __construct(string $prefix = '', array $config = [])
    {
        $this->prefix = $prefix;
        $this->debug = $config['debug'] ?? false;

        $host = $config['host'] ?? '127.0.0.1';
        $port = (int)($config['port'] ?? 6379);
        $password = $config['password'] ?? null;
        $db = (int)($config['db'] ?? 0);

        if (!class_exists('Redis')) {
            $this->log("CRITICAL: Redis extension is NOT installed.");
            return;
        }

        try {
            $this->redis = new \Redis();
            $connected = $this->redis->connect($host, $port, 2.0);

            if ($connected) {
                if ($password) {
                    $this->redis->auth($password);
                }
                if ($db) {
                    $this->redis->select($db);
                }
                $this->log("Connected to Redis at $host:$port (DB: $db)");
            } else {
                $this->log("Failed to connect to Redis at $host:$port");
                $this->redis = null;
            }

        } catch (Throwable $e) {
            $this->log("Redis Connection Exception: " . $e->getMessage());
            $this->redis = null;
        }
    }

    private function log($message)
    {
        if ($this->debug) {
            error_log("[RedisDriver] " . $message);
        }
    }

    private function key($key)
    {
        return $this->prefix . $key;
    }

    public function isAvailable(): bool
    {
        return $this->redis !== null;
    }

    public function set(string $key, mixed $value, int $ttl = 300, array $tags = []): void
    {
        if (!$this->redis) return;

        $packedValue = serialize($value);
        $fullKey = $this->key($key);

        try {
            $pipeline = $this->redis->multi(\Redis::PIPELINE);
            $pipeline->set($fullKey, $packedValue);
            $pipeline->expire($fullKey, $ttl);

            foreach ($tags as $tag) {
                $tagKey = $this->key("tag:$tag");
                $pipeline->sAdd($tagKey, $fullKey);
                $pipeline->expire($tagKey, 604800); // 7 days for tag sets
            }

            $pipeline->exec();
            $this->log("PUT: $key (TTL: $ttl)");
        } catch (Throwable $e) {
            $this->log("PUT Failed: " . $e->getMessage());
        }
    }

    public function get(string $key): mixed
    {
        if (!$this->redis) return null;

        try {
            $data = $this->redis->get($this->key($key));
            if ($data) {
                $this->log("HIT: $key");
                return unserialize($data);
            }
        } catch (Throwable $e) {
            $this->log("GET Failed: " . $e->getMessage());
            return null;
        }

        $this->log("MISS: $key");
        return null;
    }

    public function delete(string $key): bool
    {
        if (!$this->redis) return false;
        try {
            $result = $this->redis->del($this->key($key));
            $this->log("DELETE: $key");
            return (bool)$result;
        } catch (Throwable $e) {
            $this->log("DELETE Failed: " . $e->getMessage());
            return false;
        }
    }

    public function clearAll(): bool
    {
        if (!$this->redis) return false;
        try {
            $result = $this->redis->flushDB();
            $this->log("FLUSH DB");
            return $result;
        } catch (Throwable $e) {
            $this->log("FLUSH Failed: " . $e->getMessage());
            return false;
        }
    }

    // Additional methods needed for full functionality transfer

    public function getStats(): array
    {
        if (!$this->redis) {
            return [
                'keys' => 0,
                'memory' => '0 B',
                'status' => 'Disconnected'
            ];
        }

        try {
            $info = $this->redis->info('memory');
            $keys = $this->redis->dbSize();
            $memory = isset($info['used_memory_human']) ? $info['used_memory_human'] : ($info['used_memory'] ?? 0) . ' B';

            return [
                'keys' => $keys,
                'memory' => $memory,
                'status' => 'Connected'
            ];
        } catch (Throwable $e) {
            return [
                'keys' => 0,
                'memory' => 'Unknown',
                'status' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function invalidateTag(string $tag): void
    {
        if (!$this->redis) return;

        try {
            $fullTag = $this->key("tag:$tag");
            $keys = $this->redis->sMembers($fullTag);

            if (!empty($keys)) {
                $this->log("INVALIDATE TAG: $tag (" . count($keys) . " keys)");
                $keys[] = $fullTag;
                if (method_exists($this->redis, 'unlink')) {
                    $this->redis->unlink($keys);
                } else {
                    $this->redis->del($keys);
                }
            }
        } catch (Throwable $e) {
            $this->log("INVALIDATE TAG Failed: " . $e->getMessage());
        }
    }

    // Lock functionality for 'remember' method
    public function acquireLock(string $key, int $ttl): bool
    {
        if (!$this->redis) return false;
        return (bool) $this->redis->set($this->key("lock:$key"), 1, ['NX', 'EX' => $ttl]);
    }

    public function releaseLock(string $key): void
    {
        if (!$this->redis) return;
        $this->redis->del($this->key("lock:$key"));
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getDriverName(): string
    {
        return 'redis';
    }
}
