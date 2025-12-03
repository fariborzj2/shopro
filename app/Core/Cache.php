<?php

namespace App\Core;

use Exception;
use Throwable;

class Cache
{
    protected static $instance = null;
    protected $redis;
    protected $prefix;
    protected $debug = false;

    /**
     * Singleton accessor
     */
    public static function getInstance($prefix = 'app_v1_')
    {
        if (self::$instance === null) {
            self::$instance = new self($prefix);
        }
        return self::$instance;
    }

    public function __construct($prefix = '')
    {
        $this->prefix = $prefix;

        // 1. Defaults
        $host = $_ENV['REDIS_HOST'] ?? '127.0.0.1';
        $port = $_ENV['REDIS_PORT'] ?? 6379;
        $password = $_ENV['REDIS_PASSWORD'] ?? null;
        $db = $_ENV['REDIS_DB'] ?? 0;
        $driver = 'redis';

        // 2. Try DB Overrides (with useCache=false to prevent circular dependency)
        if (class_exists('App\Models\Setting') && class_exists('App\Core\Database')) {
            try {
                // CRITICAL: Must pass false to prevent infinite recursion
                $settings = \App\Models\Setting::getAll(false);

                if (isset($settings['cache_driver'])) $driver = $settings['cache_driver'];
                if (isset($settings['cache_debug']) && $settings['cache_debug'] == '1') $this->debug = true;

                if (!empty($settings['redis_host'])) $host = $settings['redis_host'];
                if (!empty($settings['redis_port'])) $port = $settings['redis_port'];
                if (!empty($settings['redis_password'])) $password = $settings['redis_password'];
                if (isset($settings['redis_db'])) $db = $settings['redis_db'];
            } catch (Throwable $e) {
                // DB not ready or other error
            }
        }

        if ($driver === 'disabled') {
            $this->log("Cache Driver is DISABLED.");
            $this->redis = null;
            return;
        }

        // 3. Check for Redis Extension
        if (!class_exists('Redis')) {
            $this->log("CRITICAL: Redis extension is NOT installed on this server. Cache disabled.");
            $this->redis = null;
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
            error_log("[Cache System] " . $message);
        }
    }

    private function key($key)
    {
        return $this->prefix . $key;
    }

    /**
     * Check if Redis is connected and available.
     * @return bool
     */
    public function isAvailable()
    {
        return $this->redis !== null;
    }

    /**
     * Get statistics about the cache.
     * @return array
     */
    public function getStats()
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

    public function put($key, $value, $ttl = 300, $tags = [])
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
                $pipeline->expire($tagKey, 604800);
            }

            $pipeline->exec();
            $this->log("PUT: $key (TTL: $ttl)");
        } catch (Throwable $e) {
            $this->log("PUT Failed: " . $e->getMessage());
        }
    }

    public function get($key)
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

    public function delete($key)
    {
        if (!$this->redis) return;
        try {
            $this->redis->del($this->key($key));
            $this->log("DELETE: $key");
        } catch (Throwable $e) {
             $this->log("DELETE Failed: " . $e->getMessage());
        }
    }

    public function flush()
    {
        if (!$this->redis) return;
        try {
            $this->redis->flushDB();
            $this->log("FLUSH DB");
        } catch (Throwable $e) {
            $this->log("FLUSH Failed: " . $e->getMessage());
        }
    }

    public function remember($key, $ttl, callable $callback, $tags = [])
    {
        if (!$this->redis) {
            return $callback();
        }

        $fullKey = $this->key($key);

        try {
            $data = $this->get($key);
            if ($data !== null) {
                return $data;
            }

            // Lock
            $lockKey = $this->key("lock:$key");
            $isLocked = $this->redis->set($lockKey, 1, ['NX', 'EX' => 10]);

            if ($isLocked) {
                $this->log("LOCK ACQUIRED: $key");
                try {
                    $data = $callback();

                    $pipeline = $this->redis->multi(\Redis::PIPELINE);
                    $pipeline->set($fullKey, serialize($data));
                    $pipeline->expire($fullKey, $ttl);

                    foreach ($tags as $tag) {
                        $pipeline->sAdd($this->key("tag:$tag"), $fullKey);
                        $pipeline->expire($this->key("tag:$tag"), 604800);
                    }

                    $pipeline->del($lockKey);
                    $pipeline->exec();

                    return $data;

                } catch (Throwable $e) {
                    $this->redis->del($lockKey);
                    throw $e;
                }
            } else {
                $this->log("WAITING FOR LOCK: $key");
                $attempts = 5;
                $wait = 200000;

                while ($attempts > 0) {
                    usleep($wait);
                    $data = $this->get($key);
                    if ($data !== null) {
                        return $data;
                    }
                    $attempts--;
                }

                $this->log("LOCK TIMEOUT: $key - Executing fallback");
                return $callback();
            }
        } catch (Throwable $e) {
            $this->log("REMEMBER Failed: " . $e->getMessage());
            return $callback();
        }
    }

    public function invalidateTag($tag)
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

                $this->purgeCdn($tag);
            }
        } catch (Throwable $e) {
            $this->log("INVALIDATE TAG Failed: " . $e->getMessage());
        }
    }

    protected function purgeCdn($tag)
    {
        $zoneId = $_ENV['CLOUDFLARE_ZONE_ID'] ?? null;
        $token = $_ENV['CLOUDFLARE_API_TOKEN'] ?? null;

        if (!$zoneId && class_exists('App\Models\Setting')) {
             try {
                $settings = \App\Models\Setting::getAll(false);
                $zoneId = $settings['cloudflare_zone_id'] ?? null;
                $token = $settings['cloudflare_api_token'] ?? null;
             } catch(Throwable $e) {}
        }

        if (!$zoneId || !$token) {
            return;
        }

        $this->log("CDN PURGE: $tag");

        try {
            $url = "https://api.cloudflare.com/client/v4/zones/$zoneId/purge_cache";
            $data = ['tags' => [$tag]];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
            curl_exec($ch);

            if ($this->debug && curl_errno($ch)) {
                $this->log("CDN PURGE FAILED: " . curl_error($ch));
            }

            curl_close($ch);
        } catch (Throwable $e) {
             $this->log("CDN PURGE Exception: " . $e->getMessage());
        }
    }
}
