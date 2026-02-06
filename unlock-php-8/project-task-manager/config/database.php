<?php
return [
    'connection' => 'mysql',
    'mysql' => [
        'host' => env('DB_HOST','localhost'),
        'driver' => env('DB_DRIVER',''),
        'database' => env('DB_SCHEMA',''),
        'username' => env('DB_USER','root'),
        'password' => env('DB_PASS',''),
        'port' => env('DB_PORT','3306'),
    ],
    // Other database or service
    // configurations can be added here
];