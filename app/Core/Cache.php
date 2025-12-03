<?php

namespace App\Core;

use Exception;

class Cache
{
    protected static $instance = null;
    protected $redis;
    protected $prefix;

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

        // Attempt to connect using environment variables first, then database settings (if available), then defaults.
        $host = $_ENV['REDIS_HOST'] ?? '127.0.0.1';
        $port = $_ENV['REDIS_PORT'] ?? 6379;
        $password = $_ENV['REDIS_PASSWORD'] ?? null;
        $db = $_ENV['REDIS_DB'] ?? 0;

        // Try to fetch overrides from DB Settings if not strictly defined in ENV (fallback mechanism)
        // Note: We check if Database class exists to avoid dependency loops during early boot
        if (class_exists('App\Models\Setting') && class_exists('App\Core\Database')) {
            // We use a try-catch because DB might not be ready
            try {
                $settings = \App\Models\Setting::getAll();
                if (!empty($settings['redis_host'])) $host = $settings['redis_host'];
                if (!empty($settings['redis_port'])) $port = $settings['redis_port'];
                if (!empty($settings['redis_password'])) $password = $settings['redis_password'];
                if (isset($settings['redis_db'])) $db = $settings['redis_db'];
            } catch (Exception $e) {
                // DB not ready, proceed with defaults
            }
        }

        try {
            $this->redis = new \Redis();
            $this->redis->connect($host, $port, 2.0); // 2 sec timeout

            if ($password) {
                $this->redis->auth($password);
            }

            if ($db) {
                $this->redis->select($db);
            }
        } catch (Exception $e) {
            // If Redis fails, we might fallback to file or null driver,
            // but for this implementation we log and allow it to be null (methods should handle null check)
            error_log("Redis Connection Failed: " . $e->getMessage());
            $this->redis = null;
        }
    }

    private function key($key)
    {
        return $this->prefix . $key;
    }

    /**
     * Store data in cache
     */
    public function put($key, $value, $ttl = 300, $tags = [])
    {
        if (!$this->redis) return;

        $packedValue = serialize($value);
        $fullKey = $this->key($key);

        // Native Redis Pipeline
        $pipeline = $this->redis->multi(\Redis::PIPELINE);

        $pipeline->set($fullKey, $packedValue);
        $pipeline->expire($fullKey, $ttl);

        foreach ($tags as $tag) {
            $tagKey = $this->key("tag:$tag");
            $pipeline->sAdd($tagKey, $fullKey);
            // Tags expire slightly later to prevent orphan keys, or same as content
            $pipeline->expire($tagKey, $ttl);
        }

        $pipeline->exec();
    }

    public function get($key)
    {
        if (!$this->redis) return null;

        $data = $this->redis->get($this->key($key));
        return $data ? unserialize($data) : null;
    }

    public function delete($key)
    {
        if (!$this->redis) return;
        $this->redis->del($this->key($key));
    }

    public function flush()
    {
        if (!$this->redis) return;
        $this->redis->flushDB();
    }

    /**
     * Remember method with Stampede Protection (Locking + Retry)
     */
    public function remember($key, $ttl, callable $callback, $tags = [])
    {
        if (!$this->redis) {
            return $callback();
        }

        $fullKey = $this->key($key);

        // 1. Try to get directly
        $data = $this->get($key);
        if ($data !== null) {
            return $data;
        }

        // 2. Acquire Lock
        $lockKey = $this->key("lock:$key");
        // set(key, value, ['NX', 'EX'=>10]) returns true on success
        $isLocked = $this->redis->set($lockKey, 1, ['NX', 'EX' => 10]);

        if ($isLocked) {
            // ---> Leader Process
            try {
                $data = $callback();

                $pipeline = $this->redis->multi(\Redis::PIPELINE);
                $pipeline->set($fullKey, serialize($data));
                $pipeline->expire($fullKey, $ttl);

                foreach ($tags as $tag) {
                    $pipeline->sAdd($this->key("tag:$tag"), $fullKey);
                }

                // Release lock in pipeline
                $pipeline->del($lockKey);
                $pipeline->exec();

                return $data;

            } catch (Exception $e) {
                // Release lock immediately on error
                $this->redis->del($lockKey);
                throw $e;
            }
        } else {
            // ---> Follower Process (Wait Loop)
            $attempts = 5;
            $wait = 200000; // 200ms

            while ($attempts > 0) {
                usleep($wait);
                $data = $this->get($key);
                if ($data !== null) {
                    return $data;
                }
                $attempts--;
            }

            // Fallback: If still not ready, compute it locally
            return $callback();
        }
    }

    /**
     * Invalidate by Tag
     */
    public function invalidateTag($tag)
    {
        if (!$this->redis) return;

        $fullTag = $this->key("tag:$tag");
        $keys = $this->redis->sMembers($fullTag);

        if (!empty($keys)) {
            // Delete all keys associated with tag + tag itself
            $keys[] = $fullTag;
            $this->redis->del($keys);

            // Future: Integration with CDN (Cloudflare Purge)
            // self::purgeCdn($tag);
        }
    }
}
