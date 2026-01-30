<?php

namespace App\System;

class View
{
    public static function render(string $viewFile, array $variables = []): string|false
    {
        \ob_start();
        \extract($variables);
        try {
            $filePath = \getcwd() . '/' . $viewFile;
            if (!file_exists($filePath)) {
                throw new \Exception("FilePath: {$filePath} not exits");
            }
            include $filePath;
        } catch (\Throwable $e) {
            throw $e;
        }
        return \ob_get_clean();
    }
}
