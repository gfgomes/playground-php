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

    public function run(): string|false
    {
        $path = $this->getCurrentPath();
        $controllerMapping = $this->routes[$path] ?? null;

        if ($controllerMapping) {
            [$controllerClass, $method] = $controllerMapping;
            $controller = new $controllerClass();
            return $controller->$method();
        }
        return View::render('layouts/404.php');
    }

    public function getCurrentPath()
    {
        $fullPath = \parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove o prefixo do diretório do projeto
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        
        if ($scriptName !== '/' && str_starts_with($fullPath, $scriptName)) {
            $fullPath = substr($fullPath, strlen($scriptName));
        }
        
        // Garante que sempre comece com /
        return '/' . ltrim($fullPath, '/');
    }
}
