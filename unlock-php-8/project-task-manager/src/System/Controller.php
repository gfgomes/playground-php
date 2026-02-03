<?php

namespace App\System;

abstract class Controller
{
    /**
     * Name of the folder that exists
     * in the folder views
     *
     * @var string
     */
    protected ?string $controllerName = null;
    private string $controllerViewPath = 'src/Views/';
    
    public function __construct()
    {
        if (!$this->controllerName) {
            $this->controllerName = \strtolower((new \ReflectionClass($this))->getShortName());
        }
    }
    public function index(array $data = []): string|false
    {
        $viewPath = $this->controllerViewPath . $this->controllerName . '/index.php';
        return $this->render($viewPath, $data);
    }
    protected function render(string $viewPath, array $data = []): string|false
    {
        try {
            return View::render($viewPath, $data);
        } catch (\Throwable $th) {
            return $this->renderError($th);
        }
    }
    protected function renderError(\Throwable $exception): string|false
    {
        $viewErrorPath = $this->controllerViewPath . 'error/index.php';
        $viewError = [
            'error' => $exception->getMessage(),
            'error_full' => $exception->getTraceAsString(),
        ];
        return View::render($viewErrorPath, $viewError);
    }
}
