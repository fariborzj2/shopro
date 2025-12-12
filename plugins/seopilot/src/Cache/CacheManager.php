<?php

namespace SeoPilot\Enterprise\Cache;

class CacheManager
{
    public static $testing = false;
    private $redis;
    private $pendingHeaders = [];

    public function __construct($redis = null)
    {
        $this->redis = $redis;
    }

    /**
     * Get item from cache (L1: Redis, L2: File)
     */
    public function get(string $key)
    {
        // Try Redis
        if ($this->redis) {
            try {
                $value = $this->redis->get($key);
                if ($value !== false) {
                    return $value;
                }
            } catch (\Exception $e) {
                // Graceful degradation
            }
        }

        // Try File Driver
        return $this->getFile($key);
    }

    public function set(string $key, $value, int $ttl = 3600)
    {
        // Set Redis
        if ($this->redis) {
            try {
                $this->redis->setex($key, $ttl, $value);
            } catch (\Exception $e) {
                // Ignore
            }
        }

        // Set File
        $this->setFile($key, $value, $ttl);
    }

    public function setTags(array $tags)
    {
        // LiteSpeed Tagging
        $tagString = implode(',', $tags);
        $this->pendingHeaders['X-LiteSpeed-Tag'] = $tagString;

        // Allow header sending in CLI if strictly testing
        if (!headers_sent() && (php_sapi_name() !== 'cli' || self::$testing)) {
            header("X-LiteSpeed-Tag: $tagString");
        }
    }

    public function getPendingHeaders(): array
    {
        return $this->pendingHeaders;
    }

    private function getFile(string $key)
    {
        $file = $this->getFilePath($key);
        if (!file_exists($file)) {
            return null;
        }

        $fp = fopen($file, 'r');
        if (!$fp) {
            return null;
        }

        // Shared Lock for Reading to prevent reading partial writes
        if (flock($fp, LOCK_SH)) {
            $content = stream_get_contents($fp);
            flock($fp, LOCK_UN);
            fclose($fp);

            $data = json_decode($content, true);
            if ($data && $data['expires'] > time()) {
                return $data['payload'];
            }
        } else {
            fclose($fp);
        }

        return null;
    }

    private function setFile(string $key, $value, int $ttl)
    {
        $file = $this->getFilePath($key);
        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0755, true);
        }
        $data = [
            'expires' => time() + $ttl,
            'payload' => $value
        ];

        // Use Exclusive Lock for Writing
        $fp = fopen($file, 'c'); // 'c' mode is safe for advisory locking
        if ($fp) {
             if (flock($fp, LOCK_EX)) {
                 ftruncate($fp, 0); // Clear file
                 fwrite($fp, json_encode($data));
                 flock($fp, LOCK_UN);
             }
             fclose($fp);
        }
    }

    private function getFilePath(string $key): string
    {
        $hash = md5($key);
        $cacheDir = defined('PROJECT_ROOT') ? PROJECT_ROOT . '/cache/seopilot' : sys_get_temp_dir() . '/seopilot';
        return $cacheDir . '/' . $hash . '.json';
    }
}
