<?php
use App\Controllers\Home;
use App\Controllers\Login;
return [
    '/' => [Home::class, 'index'],
    '/login' => [Login::class, 'index'],
    '/login/submit' => [Login::class, 'onLogin'],
    // ... other routes
];