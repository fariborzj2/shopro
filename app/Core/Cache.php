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

        $host = $_ENV['REDIS_HOST'] ?? '127.0.0.1';
        $port = $_ENV['REDIS_PORT'] ?? 6379;
        $password = $_ENV['REDIS_PASSWORD'] ?? null;
        $db = $_ENV['REDIS_DB'] ?? 0;

        if (class_exists('App\Models\Setting') && class_exists('App\Core\Database')) {
            try {
                $settings = \App\Models\Setting::getAll();
                if (!empty($settings['redis_host'])) $host = $settings['redis_host'];
                if (!empty($settings['redis_port'])) $port = $settings['redis_port'];
                if (!empty($settings['redis_password'])) $password = $settings['redis_password'];
                if (isset($settings['redis_db'])) $db = $settings['redis_db'];
            } catch (Exception $e) {
                // DB not ready
            }
        }

        try {
            $this->redis = new \Redis();
            $this->redis->connect($host, $port, 2.0);

            if ($password) {
                $this->redis->auth($password);
            }

            if ($db) {
                $this->redis->select($db);
            }
        } catch (Exception $e) {
            error_log("Redis Connection Failed: " . $e->getMessage());
            $this->redis = null;
        }
    }

    private function key($key)
    {
        return $this->prefix . $key;
    }

    public function put($key, $value, $ttl = 300, $tags = [])
    {
        if (!$this->redis) return;

        $packedValue = serialize($value);
        $fullKey = $this->key($key);

        $pipeline = $this->redis->multi(\Redis::PIPELINE);

        $pipeline->set($fullKey, $packedValue);
        $pipeline->expire($fullKey, $ttl);

        foreach ($tags as $tag) {
            $tagKey = $this->key("tag:$tag");
            $pipeline->sAdd($tagKey, $fullKey);
            // Tag set expires after 7 days
            $pipeline->expire($tagKey, 604800);
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

    public function remember($key, $ttl, callable $callback, $tags = [])
    {
        if (!$this->redis) {
            return $callback();
        }

        $fullKey = $this->key($key);

        $data = $this->get($key);
        if ($data !== null) {
            return $data;
        }

        $lockKey = $this->key("lock:$key");
        $isLocked = $this->redis->set($lockKey, 1, ['NX', 'EX' => 10]);

        if ($isLocked) {
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

            } catch (Exception $e) {
                $this->redis->del($lockKey);
                throw $e;
            }
        } else {
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

            return $callback();
        }
    }

    public function invalidateTag($tag)
    {
        if (!$this->redis) return;

        $fullTag = $this->key("tag:$tag");
        $keys = $this->redis->sMembers($fullTag);

        if (!empty($keys)) {
            $keys[] = $fullTag;
            // Use UNLINK if available (Redis 4.0+) for better performance
            // Fallback to DEL if needed, but UNLINK is standard in modern Redis extensions
            if (method_exists($this->redis, 'unlink')) {
                $this->redis->unlink($keys);
            } else {
                $this->redis->del($keys);
            }

            // CDN Integration
            $this->purgeCdn($tag);
        }
    }

    /**
     * Purge CDN (Cloudflare) by Tag.
     */
    protected function purgeCdn($tag)
    {
        // Check for Cloudflare settings in ENV or DB
        $zoneId = $_ENV['CLOUDFLARE_ZONE_ID'] ?? null;
        $token = $_ENV['CLOUDFLARE_API_TOKEN'] ?? null;

        if (!$zoneId && class_exists('App\Models\Setting')) {
             try {
                $settings = \App\Models\Setting::getAll();
                $zoneId = $settings['cloudflare_zone_id'] ?? null;
                $token = $settings['cloudflare_api_token'] ?? null;
             } catch(Exception $e) {}
        }

        if (!$zoneId || !$token) {
            return;
        }

        // Logic to purge by tag
        // Note: Enterprise Cloudflare supports 'tags', others might need 'purge_everything' or URL list.
        // We implement 'tags' purge assuming the user has appropriate plan or uses Cache-Tags.

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

        // Execute asynchronously (timeout 1s) to not block user request
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
        // We don't care about the result for now, just fire and forget (best effort)
        curl_exec($ch);
        curl_close($ch);
    }
}
