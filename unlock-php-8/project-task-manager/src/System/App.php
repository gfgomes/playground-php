<?php

namespace App\System;

use App\Traits\Singleton;

class App
{
    use Singleton;

    public function run(): mixed {
        $router = Router::instance();
        return $router->run();
    }
}
