<?php

/**
 * Funções helper globais
 */

/**
 * Gera uma URL com o prefixo do projeto
 * 
 * @param string $path Caminho relativo (ex: '/login', '/products/123')
 * @return string URL completa com prefixo
 */
function url(string $path = ''): string
{
    $baseUrl = dirname($_SERVER['SCRIPT_NAME']);
    
    // Se não estiver na raiz, adiciona o prefixo
    if ($baseUrl !== '/') {
        return $baseUrl . $path;
    }
    
    return $path;
}

/**
 * Gera uma URL de asset (CSS, JS, imagens)
 * 
 * @param string $path Caminho do asset (ex: 'css/style.css')
 * @return string URL completa do asset
 */
function asset(string $path): string
{
    return url('/' . ltrim($path, '/'));
}
