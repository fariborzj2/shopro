<?php

declare(strict_types=1);

namespace App\Core\Cache;

interface CacheDriverInterface
{
    public function set(string $key, mixed $value, int $ttl = 300, array $tags = []): void;
    public function get(string $key): mixed;
    public function delete(string $key): bool;
    public function clearAll(): bool;

    // Add support for tags management if possible, or leave it to set implementation
    // The requirement mentions 'set' logic involves tags.
}
