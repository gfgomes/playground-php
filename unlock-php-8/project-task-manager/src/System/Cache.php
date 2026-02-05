<?php

namespace App\System;

/**
 * Cache abstrato - Escolha Redis ou Memcached
 * 
 * Exemplos de implementação:
 * @see \App\System\Examples\CacheRedisExample
 * @see \App\System\Examples\CacheMemcachedExample
 */
interface CacheInterface
{
    public static function set(string $key, mixed $value, int $ttl = 3600): bool;
    public static function get(string $key): mixed;
    public static function delete(string $key): int|bool;
    public static function exists(string $key): bool;
    public static function flush(): bool;
    public static function invalidateAll(): void;
}
