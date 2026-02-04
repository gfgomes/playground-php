<?php
use App\Controllers\Home;
use App\Controllers\Login;
use App\Controllers\Tasks;
return [
    '/' => [Home::class, 'index'],
    '/login' => [Login::class, 'index'],
    '/login/submit' => [Login::class, 'onLogin'],
    '/tasks' => [Tasks::class, 'index'],
    '/tasks/create' => [Tasks::class, 'onCreateTask'],
    '/tasks/completed' => [Tasks::class, 'onActionTask'],
    '/tasks/delete' => [Tasks::class, 'onActionTask'],
    '/tasks/delete-all' => [Tasks::class, 'onDeleteAll'],
    // ... other routes
];