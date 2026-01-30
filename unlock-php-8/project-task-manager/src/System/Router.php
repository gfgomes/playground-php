<?php

namespace App\System;

use App\Traits\Singleton;

class Router
{
    use Singleton;
    private array $routes = [];

    private function init(): void
    {
        $this->routes = require \getcwd() . '/src/routes.php';
    }
}
