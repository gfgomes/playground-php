<?php

namespace App\Middleware;

use App\System\Redirect;
use App\System\Router;

class Authentication
{
    private $unauthenticatedRoutes = [
        '/login',
        '/login/submit',
    ];
    public function handle()
    {
        $router = Router::instance();
        if (in_array($router->getCurrentPath(), $this->unauthenticatedRoutes)) {
            return;
        }
        // Check if the user is logged in
        if (!isset($_SESSION['userLogged'])) {
            return Redirect::to('/login');
        }
    }
}
