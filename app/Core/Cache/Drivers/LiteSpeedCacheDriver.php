<?php

declare(strict_types=1);

namespace App\Core\Cache\Drivers;

use App\Core\Cache\CacheDriverInterface;

class LiteSpeedCacheDriver implements CacheDriverInterface
{
    public function set(string $key, mixed $value, int $ttl = 300, array $tags = []): void
    {
        // Ignore $value as LSCache caches the page output.
        // Send Cache-Control header
        header("X-LiteSpeed-Cache-Control: public, max-age=$ttl");

        // Send Tag header for purging
        if (!empty($tags)) {
            // Also include the specific key as a tag to allow single item purging
            $allTags = array_merge([$key], $tags);
            $tagString = implode(',', array_unique($allTags));
            header("X-LiteSpeed-Tag: $tagString");
        } else {
            header("X-LiteSpeed-Tag: $key");
        }
    }

    public function get(string $key): mixed
    {
        // Always return null to force PHP execution (cache miss behavior)
        // LSCache handles hits before PHP starts.
        return null;
    }

    public function delete(string $key): bool
    {
        // Purge by tag (using the key as the tag)
        header("X-LiteSpeed-Purge: $key");
        return true;
    }

    public function clearAll(): bool
    {
        header("X-LiteSpeed-Purge: *");
        return true;
    }

    public function isAvailable(): bool
    {
        return !headers_sent();
    }

    // Allow purging by specific tag
    public function invalidateTag(string $tag): void
    {
        header("X-LiteSpeed-Purge: $tag");
    }
}
