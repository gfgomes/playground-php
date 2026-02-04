<?php

namespace App\Middlewares;

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
        $currentPath = $router->getCurrentPath();
        
        if (in_array($currentPath, $this->unauthenticatedRoutes)) {
            return;
        }
        
        // Check if the user is logged in
        if (!isset($_SESSION['userLogged'])) {
            Redirect::to('/login');
        }
    }
}
