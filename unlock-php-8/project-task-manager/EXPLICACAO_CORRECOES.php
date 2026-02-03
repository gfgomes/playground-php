<?php
/**
 * ============================================================================
 * EXPLICAÇÃO DE TODAS AS CORREÇÕES FEITAS NA APLICAÇÃO
 * ============================================================================
 */

// ============================================================================
// ERRO 1: Caminho do autoload.php incorreto
// ============================================================================

// ❌ ANTES (index.php - linha 7):
require '../vendor/autoload.php';

// ✅ DEPOIS:
require __DIR__ . '/vendor/autoload.php';

/**
 * EXPLICAÇÃO:
 * 
 * A estrutura do projeto é:
 * project-task-manager/
 * ├── index.php
 * └── vendor/
 *     └── autoload.php
 * 
 * '../vendor/autoload.php' significa:
 * - Suba uma pasta (..)
 * - Entre em vendor
 * - Isso buscaria em: playground-php/unlock-php-8/vendor/ (ERRADO!)
 * 
 * '__DIR__ . /vendor/autoload.php' significa:
 * - Pegue o diretório atual do arquivo (__DIR__)
 * - Entre em vendor
 * - Isso busca em: project-task-manager/vendor/ (CORRETO!)
 * 
 * __DIR__ é uma constante mágica que retorna o diretório do arquivo atual
 */


// ============================================================================
// ERRO 2: View::render() recebendo rota em vez de arquivo
// ============================================================================

// ❌ ANTES (Router.php - linha 27):
return View::render('/');

// ✅ DEPOIS:
return View::render('layouts/404.php');

/**
 * EXPLICAÇÃO:
 * 
 * O método View::render() funciona assim:
 * 
 * public static function render(string $viewFile, array $variables = []): string|false
 * {
 *     $filePath = getcwd() . '/' . $viewFile;
 *     include $filePath;
 * }
 * 
 * Quando você passa '/':
 * $filePath = "C:\laragon\www\...\project-task-manager" . "/" . "/"
 * $filePath = "C:\laragon\www\...\project-task-manager//"
 * include "C:\laragon\www\...\project-task-manager//"  // ❌ Tenta incluir um DIRETÓRIO!
 * 
 * Quando você passa 'layouts/404.php':
 * $filePath = "C:\laragon\www\...\project-task-manager" . "/" . "layouts/404.php"
 * $filePath = "C:\laragon\www\...\project-task-manager/layouts/404.php"
 * include "C:\laragon\www\...\project-task-manager/layouts/404.php"  // ✅ Inclui um ARQUIVO!
 * 
 * RESUMO: View::render() espera CAMINHO DE ARQUIVO, não ROTA
 */


// ============================================================================
// ERRO 3: Router não remove prefixo do diretório do projeto
// ============================================================================

// ❌ ANTES (Router.php - getCurrentPath()):
public function getCurrentPath()
{
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

// ✅ DEPOIS:
public function getCurrentPath()
{
    $fullPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // Remove o prefixo do diretório do projeto
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    
    if ($scriptName !== '/' && str_starts_with($fullPath, $scriptName)) {
        $fullPath = substr($fullPath, strlen($scriptName));
    }
    
    // Garante que sempre comece com /
    return '/' . ltrim($fullPath, '/');
}

/**
 * EXPLICAÇÃO DETALHADA:
 * 
 * Quando você acessa:
 * http://localhost/playground-php/unlock-php-8/project-task-manager/
 * 
 * ANTES da correção:
 * $_SERVER['REQUEST_URI'] = "/playground-php/unlock-php-8/project-task-manager/"
 * parse_url(..., PHP_URL_PATH) = "/playground-php/unlock-php-8/project-task-manager/"
 * getCurrentPath() retorna = "/playground-php/unlock-php-8/project-task-manager/"
 * 
 * Mas suas rotas são:
 * '/' => [Home::class, 'index']
 * '/login' => [Login::class, 'index']
 * 
 * "/playground-php/unlock-php-8/project-task-manager/" !== "/"  ❌ NÃO ENCONTRA A ROTA!
 * 
 * 
 * DEPOIS da correção:
 * 
 * Passo 1: Pegar o caminho completo
 * $fullPath = "/playground-php/unlock-php-8/project-task-manager/"
 * 
 * Passo 2: Pegar o diretório do script
 * $_SERVER['SCRIPT_NAME'] = "/playground-php/unlock-php-8/project-task-manager/index.php"
 * dirname($_SERVER['SCRIPT_NAME']) = "/playground-php/unlock-php-8/project-task-manager"
 * $scriptName = "/playground-php/unlock-php-8/project-task-manager"
 * 
 * Passo 3: Verificar se o caminho começa com o diretório do script
 * str_starts_with("/playground-php/unlock-php-8/project-task-manager/", 
 *                 "/playground-php/unlock-php-8/project-task-manager") = true
 * 
 * Passo 4: Remover o prefixo
 * strlen("/playground-php/unlock-php-8/project-task-manager") = 48
 * substr("/playground-php/unlock-php-8/project-task-manager/", 48) = "/"
 * $fullPath = "/"
 * 
 * Passo 5: Garantir que comece com /
 * ltrim("/", "/") = ""
 * "/" . "" = "/"
 * 
 * getCurrentPath() retorna = "/"  ✅ ENCONTRA A ROTA!
 * 
 * 
 * OUTRO EXEMPLO - Acessando /login:
 * http://localhost/playground-php/unlock-php-8/project-task-manager/login
 * 
 * $fullPath = "/playground-php/unlock-php-8/project-task-manager/login"
 * $scriptName = "/playground-php/unlock-php-8/project-task-manager"
 * substr("/playground-php/unlock-php-8/project-task-manager/login", 48) = "/login"
 * getCurrentPath() retorna = "/login"  ✅ ENCONTRA A ROTA!
 */


// ============================================================================
// ERRO 4: Propriedade tipada não inicializada
// ============================================================================

// ❌ ANTES (Controller.php - linha 13):
protected ?string $controllerName;

public function __construct()
{
    if (!$this->controllerName) {  // ❌ ERRO: Acesso antes de inicializar
        $this->controllerName = strtolower((new \ReflectionClass($this))->getShortName());
    }
}

// ✅ DEPOIS:
protected ?string $controllerName = null;

public function __construct()
{
    if (!$this->controllerName) {  // ✅ Agora funciona
        $this->controllerName = strtolower((new \ReflectionClass($this))->getShortName());
    }
}

/**
 * EXPLICAÇÃO:
 * 
 * No PHP 7.4+, propriedades TIPADAS devem ser inicializadas antes de serem acessadas.
 * 
 * ANTES:
 * protected ?string $controllerName;  // Declarada mas NÃO inicializada
 * 
 * Quando você tenta acessar:
 * if (!$this->controllerName) { ... }
 * 
 * PHP lança erro:
 * "Typed property App\System\Controller::$controllerName must not be accessed 
 *  before initialization"
 * 
 * 
 * DEPOIS:
 * protected ?string $controllerName = null;  // Declarada E inicializada com null
 * 
 * Agora você pode acessar:
 * if (!$this->controllerName) { ... }  // null é falsy, então entra no if
 * 
 * 
 * POR QUE ISSO ACONTECE?
 * 
 * PHP 7.3 e anteriores:
 * - Propriedades não tipadas tinham valor null por padrão
 * - Você podia acessar sem inicializar
 * 
 * PHP 7.4+:
 * - Propriedades tipadas NÃO têm valor padrão
 * - Você DEVE inicializar explicitamente
 * - Isso evita bugs de acesso acidental a propriedades não inicializadas
 * 
 * 
 * TIPOS NULLABLE:
 * ?string significa "string OU null"
 * 
 * Você pode:
 * protected ?string $controllerName = null;  // ✅ Inicializa com null
 * protected ?string $controllerName = "home";  // ✅ Inicializa com string
 * 
 * Você NÃO pode:
 * protected ?string $controllerName;  // ❌ Não inicializada (erro ao acessar)
 */


// ============================================================================
// RESUMO DE TODAS AS CORREÇÕES
// ============================================================================

/**
 * 1. index.php (linha 7):
 *    - Corrigido caminho do autoload.php
 *    - De: '../vendor/autoload.php'
 *    - Para: __DIR__ . '/vendor/autoload.php'
 * 
 * 2. Router.php (linha 27):
 *    - Corrigido parâmetro do View::render()
 *    - De: View::render('/')
 *    - Para: View::render('layouts/404.php')
 * 
 * 3. Router.php (método getCurrentPath):
 *    - Adicionada lógica para remover prefixo do diretório
 *    - Agora remove "/playground-php/unlock-php-8/project-task-manager" do caminho
 *    - Retorna apenas a rota relativa (ex: "/" ou "/login")
 * 
 * 4. Controller.php (linha 13):
 *    - Inicializada propriedade tipada
 *    - De: protected ?string $controllerName;
 *    - Para: protected ?string $controllerName = null;
 */


// ============================================================================
// CONCEITOS IMPORTANTES APRENDIDOS
// ============================================================================

/**
 * 1. __DIR__ vs caminhos relativos:
 *    - __DIR__ sempre aponta para o diretório do arquivo atual
 *    - Caminhos relativos (.., .) dependem do diretório de execução
 *    - Use __DIR__ para caminhos de arquivos no mesmo projeto
 * 
 * 2. Diferença entre ROTA e CAMINHO DE ARQUIVO:
 *    - Rota: "/" (URL que o usuário acessa)
 *    - Arquivo: "layouts/404.php" (arquivo físico no servidor)
 *    - Não confunda os dois!
 * 
 * 3. Roteamento em subdiretórios:
 *    - Quando o projeto não está na raiz do servidor
 *    - Você precisa remover o prefixo do diretório
 *    - Use $_SERVER['SCRIPT_NAME'] para descobrir o prefixo
 * 
 * 4. Propriedades tipadas no PHP 7.4+:
 *    - DEVEM ser inicializadas antes de acessar
 *    - Use = null para propriedades nullable
 *    - Isso previne bugs de acesso acidental
 * 
 * 5. Constantes mágicas úteis:
 *    - __DIR__: diretório do arquivo atual
 *    - __FILE__: caminho completo do arquivo atual
 *    - ::class: nome completo da classe
 * 
 * 6. Variáveis de servidor úteis:
 *    - $_SERVER['REQUEST_URI']: URL completa requisitada
 *    - $_SERVER['SCRIPT_NAME']: caminho do script PHP executado
 *    - parse_url(): extrai partes de uma URL
 */
