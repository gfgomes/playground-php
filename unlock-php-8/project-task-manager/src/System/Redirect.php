<?php

namespace App\System;

class Redirect
{
    public static function to(string $url, array $params = []): void
    {
        // Adiciona o prefixo do diretório do projeto
        $baseUrl = dirname($_SERVER['SCRIPT_NAME']);
        
        // Se não estiver na raiz, adiciona o prefixo
        if ($baseUrl !== '/') {
            $url = $baseUrl . $url;
        }
        
        if (!empty($params)) {
            $queryString = \http_build_query($params);
            $url .= '?' . $queryString;
        }
        \header('Location: ' . $url);
        exit;
    }
}
