<?php
/**
 * ============================================================================
 * FRAMEWORK MVC - COMENTADO LINHA POR LINHA
 * ============================================================================
 * 
 * Este arquivo explica CADA LINHA do framework, seguindo o fluxo de execução.
 * Vamos seguir o caminho: index.php → App → Router → Controller → View
 */


// ============================================================================
// ARQUIVO 1: index.php (PONTO DE ENTRADA)
// ============================================================================
// Este é o PRIMEIRO arquivo executado quando você acessa qualquer URL

<?php

// Linha 1: Tag de abertura PHP
// Tudo que vem depois é código PHP

// Linha 3: Ativa modo strict (tipos estritos)
declare(strict_types=1);
// Exemplo: function soma(int $a, int $b) só aceita inteiros
// sem strict_types: soma("5", "10") funciona (converte string para int)
// com strict_types: soma("5", "10") dá erro (exige int)

// Linha 5: Importa a classe App do namespace App\System
use \App\System\App;
// Agora você pode usar "App" em vez de "\App\System\App"
// É como um "atalho" para a classe

// Linha 7: Carrega o autoloader do Composer
require __DIR__ . '/vendor/autoload.php';
// __DIR__ = diretório do arquivo atual (ex: C:\laragon\www\project-task-manager)
// Composer autoload permite usar classes sem fazer require manual
// Quando você faz "new App()", o Composer carrega automaticamente o arquivo App.php

// Linha 8: Cria UMA ÚNICA instância da classe App (padrão Singleton)
$app = App::instance();
// App::instance() é um método ESTÁTICO que retorna sempre a MESMA instância
// É diferente de "new App()" que criaria uma NOVA instância toda vez
// Singleton garante que só existe UM objeto App na aplicação inteira

// Linha 9: Executa a aplicação e exibe o resultado (HTML)
echo $app->run();
// $app->run() processa a requisição e retorna HTML
// echo exibe esse HTML no navegador
// É aqui que a "mágica" acontece!


// ============================================================================
// ARQUIVO 2: src/Traits/Singleton.php (PADRÃO SINGLETON)
// ============================================================================
// Trait que implementa o padrão Singleton (uma única instância)

<?php

// Linha 1: Tag de abertura PHP

// Linha 3: Define o namespace (organização de classes)
namespace App\Traits;
// Este trait está em App\Traits\Singleton
// Namespaces são como "pastas virtuais" para organizar código

// Linha 5: Define um trait (código reutilizável)
trait Singleton
// Trait é como uma "classe parcial" que pode ser incluída em outras classes
// Diferente de herança, você pode usar múltiplos traits
// Uso: class MinhaClasse { use Singleton; }

{
    // Linha 7: Propriedade estática privada que guarda a instância única
    private static $instance = null;
    // static = pertence à CLASSE, não ao objeto
    // Todas as instâncias compartilham essa variável
    // Inicialmente é null (nenhuma instância criada ainda)

    // Linha 9: Construtor protegido (não pode ser chamado de fora)
    final protected function __construct()
    // protected = só pode ser chamado dentro da classe ou classes filhas
    // final = não pode ser sobrescrito por classes filhas
    // Isso IMPEDE fazer "new App()" de fora da classe
    {
        // Linha 11: Chama o método init() se existir
        $this->init();
        // Permite que classes que usam o trait façam inicializações
        // Se a classe não tiver init(), não faz nada
    }

    // Linha 14: Método estático público para obter a instância
    final public static function instance(): mixed
    // static = pode ser chamado sem criar objeto (App::instance())
    // public = pode ser chamado de qualquer lugar
    // mixed = pode retornar qualquer tipo (neste caso, retorna a instância da classe)
    {
        // Linha 16: Verifica se já existe uma instância
        if (self::$instance === null) {
        // self::$instance = acessa a propriedade estática $instance
        // === null = verifica se ainda não foi criada
        
            // Linha 17: Cria a primeira (e única) instância
            self::$instance = new static();
            // new static() = cria instância da classe que está usando o trait
            // Se App usa Singleton, cria new App()
            // Se Router usa Singleton, cria new Router()
            // Chama o __construct() que por sua vez chama init()
        }
        
        // Linha 19: Retorna a instância (sempre a mesma)
        return self::$instance;
        // Na primeira chamada: cria e retorna
        // Nas próximas chamadas: apenas retorna a instância já criada
    }

    // Linha 22: Método vazio que pode ser sobrescrito
    protected function init(): void
    // void = não retorna nada
    // Classes que usam Singleton podem implementar este método
    // É chamado automaticamente no construtor
    {
        // Linha 24: Comentário explicativo
        //Initialize the singleton free from constructor parameters.
        // Este método fica vazio no trait
        // Cada classe implementa sua própria lógica de inicialização
    }
}


// ============================================================================
// ARQUIVO 3: src/System/App.php (CLASSE PRINCIPAL)
// ============================================================================
// Classe principal que inicia a aplicação

<?php

// Linha 1: Tag de abertura PHP

// Linha 3: Define o namespace
namespace App\System;
// Esta classe está em App\System\App

// Linha 5: Importa o trait Singleton
use App\Traits\Singleton;
// Agora pode usar "Singleton" em vez de "App\Traits\Singleton"

// Linha 7: Define a classe App
class App
{
    // Linha 9: Usa o trait Singleton
    use Singleton;
    // Isso "copia" todo o código do trait Singleton para dentro desta classe
    // App agora tem: $instance, __construct(), instance(), init()

    // Linha 11: Método principal que executa a aplicação
    public function run(): mixed
    // public = pode ser chamado de fora da classe
    // mixed = pode retornar qualquer tipo
    {
        // Linha 12: Cria/obtém a instância única do Router
        $router = Router::instance();
        // Router também usa Singleton
        // Na primeira vez: cria o Router e chama init() que carrega as rotas
        // Nas próximas: retorna a mesma instância
        
        // Linha 13: Executa o router e retorna o resultado
        return $router->run();
        // $router->run() processa a URL e retorna HTML
        // Este HTML é retornado para index.php que faz echo
    }
}


// ============================================================================
// ARQUIVO 4: src/routes.php (DEFINIÇÃO DE ROTAS)
// ============================================================================
// Arquivo que mapeia URLs para Controllers

<?php

// Linha 1: Tag de abertura PHP

// Linha 2: Importa a classe Home
use App\Controllers\Home;
// Atalho para App\Controllers\Home

// Linha 3: Importa a classe Login
use App\Controllers\Login;
// Atalho para App\Controllers\Login

// Linha 4: RETORNA um array associativo
return [
// Este array é capturado pelo "require" no Router
// Estrutura: 'URL' => [Classe, 'método']

    // Linha 5: Rota para a home
    '/' => [Home::class, 'index'],
    // '/' = URL raiz (http://localhost/project/)
    // Home::class = "App\Controllers\Home" (nome completo da classe)
    // 'index' = nome do método a ser chamado
    // Quando acessar "/", chama Home->index()
    
    // Linha 6: Rota para login
    '/login' => [Login::class, 'index'],
    // Quando acessar "/login", chama Login->index()
    
    // Linha 7: Rota para processar login
    '/login/submit' => [Login::class, 'onLogin'],
    // Quando acessar "/login/submit", chama Login->onLogin()
    
    // Linha 8: Comentário sobre outras rotas
    // ... other routes
];
// O array é retornado e capturado por: $this->routes = require 'routes.php'


// ============================================================================
// ARQUIVO 5: src/System/Router.php (SISTEMA DE ROTAS)
// ============================================================================
// Classe que processa URLs e chama os Controllers corretos

<?php

// Linha 1: Tag de abertura PHP

// Linha 3: Define o namespace
namespace App\System;

// Linha 5: Importa o trait Singleton
use App\Traits\Singleton;

// Linha 7: Define a classe Router
class Router
{
    // Linha 9: Usa o trait Singleton
    use Singleton;
    // Router agora tem: $instance, __construct(), instance(), init()
    
    // Linha 10: Propriedade privada que guarda as rotas
    private array $routes = [];
    // array = tipo array (PHP 7.4+)
    // $routes = [] = array vazio inicialmente
    // Será preenchido pelo método init()

    // Linha 12: Método de inicialização (chamado pelo Singleton)
    private function init(): void
    // private = só pode ser chamado dentro desta classe
    // void = não retorna nada
    // É chamado automaticamente pelo __construct() do Singleton
    {
        // Linha 14: Carrega o arquivo de rotas
        $this->routes = require \getcwd() . '/src/routes.php';
        // getcwd() = retorna o diretório de trabalho atual
        // Ex: C:\laragon\www\project-task-manager
        // '/src/routes.php' = caminho relativo
        // require executa o arquivo e RETORNA o array
        // $this->routes agora contém:
        // [
        //     '/' => ['App\Controllers\Home', 'index'],
        //     '/login' => ['App\Controllers\Login', 'index'],
        // ]
    }

    // Linha 17: Método principal que processa a requisição
    public function run(): string|false
    // string|false = pode retornar string OU false (union type PHP 8+)
    {
        // Linha 19: Pega o caminho da URL atual
        $path = $this->getCurrentPath();
        // Exemplo: se acessou http://localhost/project/login
        // $path = "/login"
        
        // Linha 20: Busca a rota no array de rotas
        $controllerMapping = $this->routes[$path] ?? null;
        // $this->routes["/login"] = ['App\Controllers\Login', 'index']
        // ?? null = se não existir, retorna null (operador null coalescing)
        // Se a rota não existir, $controllerMapping = null

        // Linha 22: Verifica se encontrou a rota
        if ($controllerMapping) {
        // Se $controllerMapping não for null, false, 0, "", etc.
        
            // Linha 23: Desestrutura o array em duas variáveis
            [$controllerClass, $method] = $controllerMapping;
            // $controllerMapping = ['App\Controllers\Login', 'index']
            // $controllerClass = 'App\Controllers\Login'
            // $method = 'index'
            // É o mesmo que:
            // $controllerClass = $controllerMapping[0];
            // $method = $controllerMapping[1];
            
            // Linha 24: Instancia o controller DINAMICAMENTE
            $controller = new $controllerClass();
            // $controllerClass = "App\Controllers\Login"
            // new $controllerClass() = new App\Controllers\Login()
            // Cria um objeto da classe Login
            // Chama o __construct() do Login (que herda de Controller)
            
            // Linha 25: Chama o método DINAMICAMENTE e retorna o resultado
            return $controller->$method();
            // $method = "index"
            // $controller->$method() = $controller->index()
            // Chama o método index() do controller Login
            // O método retorna HTML que é retornado aqui
        }
        
        // Linha 27: Se não encontrou a rota, exibe página 404
        return View::render('layouts/404.php');
        // View::render() carrega e executa o arquivo 404.php
        // Retorna o HTML da página de erro
    }

    // Linha 30: Método que extrai o caminho limpo da URL
    public function getCurrentPath()
    {
        // Linha 32: Extrai o caminho da URL
        $fullPath = \parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // $_SERVER['REQUEST_URI'] = URL completa requisitada
        // Ex: "/playground-php/unlock-php-8/project-task-manager/login?id=5"
        // parse_url(..., PHP_URL_PATH) = extrai apenas o caminho (sem query string)
        // $fullPath = "/playground-php/unlock-php-8/project-task-manager/login"
        
        // Linha 34: Comentário explicativo
        // Remove o prefixo do diretório do projeto
        
        // Linha 35: Pega o diretório do script
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        // $_SERVER['SCRIPT_NAME'] = caminho do script executado
        // Ex: "/playground-php/unlock-php-8/project-task-manager/index.php"
        // dirname() = pega apenas o diretório (remove o arquivo)
        // $scriptName = "/playground-php/unlock-php-8/project-task-manager"
        
        // Linha 37: Verifica se precisa remover o prefixo
        if ($scriptName !== '/' && str_starts_with($fullPath, $scriptName)) {
        // $scriptName !== '/' = se não estiver na raiz do servidor
        // str_starts_with($fullPath, $scriptName) = se o caminho começa com o prefixo
        // Exemplo:
        // $fullPath = "/playground-php/unlock-php-8/project-task-manager/login"
        // $scriptName = "/playground-php/unlock-php-8/project-task-manager"
        // str_starts_with() = true (começa com o prefixo)
        
            // Linha 38: Remove o prefixo do caminho
            $fullPath = substr($fullPath, strlen($scriptName));
            // strlen($scriptName) = tamanho do prefixo (48 caracteres)
            // substr($fullPath, 48) = pega tudo a partir da posição 48
            // "/playground-php/unlock-php-8/project-task-manager/login"
            //                                                    ^^^^^^
            //                                                    posição 48
            // $fullPath = "/login"
        }
        
        // Linha 41: Comentário explicativo
        // Garante que sempre comece com /
        
        // Linha 42: Normaliza o caminho
        return '/' . ltrim($fullPath, '/');
        // ltrim($fullPath, '/') = remove TODAS as barras da esquerda
        // Se $fullPath = "/login" → ltrim = "login"
        // Se $fullPath = "//login" → ltrim = "login"
        // Se $fullPath = "login" → ltrim = "login"
        // '/' . "login" = "/login"
        // GARANTE que sempre retorna com UMA barra no início
        // Resultado final: "/login"
    }
}


// ============================================================================
// RESUMO DO FLUXO COMPLETO
// ============================================================================

/**
 * USUÁRIO ACESSA: http://localhost/project-task-manager/login
 * 
 * 1. index.php
 *    - require autoload.php (carrega Composer)
 *    - $app = App::instance() (cria App usando Singleton)
 *    - echo $app->run() (executa e exibe resultado)
 * 
 * 2. App::instance() (Singleton)
 *    - Verifica se já existe instância
 *    - Se não existe: new App() → chama __construct() → chama init()
 *    - Retorna a instância
 * 
 * 3. $app->run() (App)
 *    - $router = Router::instance() (cria Router usando Singleton)
 *    - return $router->run() (delega para o Router)
 * 
 * 4. Router::instance() (Singleton)
 *    - Verifica se já existe instância
 *    - Se não existe: new Router() → chama __construct() → chama init()
 *    - init() carrega: $this->routes = require 'routes.php'
 *    - Retorna a instância
 * 
 * 5. $router->run() (Router)
 *    - $path = getCurrentPath() → "/login"
 *    - $controllerMapping = $routes["/login"] → ['App\Controllers\Login', 'index']
 *    - [$controllerClass, $method] = $controllerMapping
 *    - $controller = new Login() (instancia dinamicamente)
 *    - return $controller->index() (chama método dinamicamente)
 * 
 * 6. Login->index() (Controller)
 *    - Processa a lógica
 *    - return $this->render('layouts/login.php')
 * 
 * 7. View::render() (View)
 *    - ob_start() (inicia buffer)
 *    - include 'layouts/login.php' (executa o arquivo)
 *    - return ob_get_clean() (retorna HTML capturado)
 * 
 * 8. HTML retorna para index.php
 *    - echo $html (exibe no navegador)
 * 
 * 9. Navegador exibe a página de login
 */


// ============================================================================
// CONCEITOS-CHAVE EXPLICADOS
// ============================================================================

/**
 * 1. SINGLETON:
 *    - Garante UMA ÚNICA instância da classe
 *    - Usa propriedade estática para guardar a instância
 *    - Construtor privado/protegido impede "new" de fora
 *    - Método instance() retorna sempre a mesma instância
 * 
 * 2. TRAIT:
 *    - Código reutilizável que pode ser incluído em classes
 *    - Diferente de herança (pode usar múltiplos traits)
 *    - Uso: class MinhaClasse { use MeuTrait; }
 * 
 * 3. NAMESPACE:
 *    - Organiza classes em "pastas virtuais"
 *    - Evita conflito de nomes
 *    - App\System\Router = pasta App/System, classe Router
 * 
 * 4. AUTOLOADING:
 *    - Composer carrega classes automaticamente
 *    - Não precisa fazer require manual
 *    - Basta usar "use" e "new"
 * 
 * 5. INSTANCIAÇÃO DINÂMICA:
 *    - $classe = "MinhaClasse"; new $classe();
 *    - Cria objetos baseado em strings
 *    - Usado no Router para instanciar controllers
 * 
 * 6. CHAMADA DE MÉTODO DINÂMICA:
 *    - $metodo = "index"; $obj->$metodo();
 *    - Chama métodos baseado em strings
 *    - Usado no Router para chamar métodos dos controllers
 * 
 * 7. REQUIRE COM RETURN:
 *    - Arquivo pode retornar um valor
 *    - $dados = require 'config.php';
 *    - Usado para carregar configurações e rotas
 * 
 * 8. DESESTRUTURAÇÃO DE ARRAY:
 *    - [$a, $b] = [1, 2];
 *    - $a = 1, $b = 2
 *    - Usado para extrair classe e método da rota
 * 
 * 9. NULL COALESCING (??):
 *    - $valor = $array['chave'] ?? 'padrão';
 *    - Se não existir, usa o valor padrão
 *    - Evita erros de chave inexistente
 * 
 * 10. UNION TYPES (|):
 *     - function teste(): string|false
 *     - Pode retornar string OU false
 *     - PHP 8+
 */
