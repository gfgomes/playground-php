<?php

// namespace App\System;

// class Cache
// {
//     private static ?\Redis $instance = null;
//     private static ?int $version = null;

//     private static function connect(): \Redis
//     {
//         if (self::$instance === null) {
//             self::$instance = new \Redis();
//             self::$instance->connect('localhost', 6379);
//             self::$instance->select(0);
//         }
//         return self::$instance;
//     }

//     private static function getVersion(): int
//     {
//         if (self::$version === null) {
//             $version = self::connect()->get('cache:version');
//             self::$version = $version ? (int)$version : 1;
//         }
//         return self::$version;
//     }

//     private static function key(string $key): string
//     {
//         return 'v' . self::getVersion() . ':task_manager:' . $key;
//     }

//     public static function set(string $key, mixed $value, int $ttl = 3600): bool
//     {
//         return self::connect()->setex(self::key($key), $ttl, serialize($value));
//     }

//     public static function get(string $key): mixed
//     {
//         $value = self::connect()->get(self::key($key));
//         return $value ? unserialize($value) : false;
//     }

//     public static function delete(string $key): int
//     {
//         return self::connect()->del(self::key($key));
//     }

//     public static function exists(string $key): bool
//     {
//         return self::connect()->exists(self::key($key)) > 0;
//     }

//     public static function flush(): bool
//     {
//         return self::connect()->flushDB();
//     }

//     public static function invalidateAll(): void
//     {
//         $newVersion = self::getVersion() + 1;
//         self::connect()->set('cache:version', $newVersion);
//         self::$version = $newVersion;
//     }
// }
