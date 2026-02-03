<?php
/**
 * ============================================================================
 * GUIA COMPLETO: COMO O FRAMEWORK MVC FUNCIONA (PARA INICIANTES)
 * ============================================================================
 * 
 * Este guia explica PASSO A PASSO como o framework funciona, desde quando
 * o usuário acessa uma URL até a página ser exibida.
 */

// ============================================================================
// PARTE 1: O QUE É MVC?
// ============================================================================

/**
 * MVC = Model-View-Controller (Modelo-Visão-Controlador)
 * 
 * É um padrão de arquitetura que separa a aplicação em 3 camadas:
 * 
 * 1. MODEL (Modelo):
 *    - Representa os DADOS da aplicação
 *    - Faz operações no banco de dados (CRUD: Create, Read, Update, Delete)
 *    - Exemplo: User.php, Product.php, Order.php
 * 
 * 2. VIEW (Visão):
 *    - Representa a INTERFACE que o usuário vê (HTML, CSS)
 *    - Exibe os dados que vêm do Controller
 *    - Exemplo: home/index.php, login.php, 404.php
 * 
 * 3. CONTROLLER (Controlador):
 *    - É o INTERMEDIÁRIO entre Model e View
 *    - Recebe requisições do usuário
 *    - Busca dados no Model
 *    - Envia dados para a View
 *    - Exemplo: Home.php, Login.php, Product.php
 * 
 * FLUXO:
 * Usuário → Controller → Model (busca dados) → Controller → View (exibe)
 */


// ============================================================================
// PARTE 2: ESTRUTURA DO PROJETO
// ============================================================================

/**
 * project-task-manager/
 * ├── index.php                    ← Ponto de entrada (tudo começa aqui)
 * ├── composer.json                ← Configuração do Composer
 * ├── vendor/                      ← Dependências do Composer
 * │   └── autoload.php            ← Autoload de classes
 * ├── layouts/                     ← Templates de layout
 * │   ├── header.php
 * │   ├── footer.php
 * │   ├── login.php
 * │   └── 404.php
 * └── src/
 *     ├── routes.php               ← Definição de rotas
 *     ├── Controllers/             ← CONTROLLERS (C do MVC)
 *     │   ├── Home.php
 *     │   └── Login.php
 *     ├── Models/                  ← MODELS (M do MVC)
 *     │   └── User.php
 *     ├── Views/                   ← VIEWS (V do MVC)
 *     │   ├── home/
 *     │   │   └── index.php
 *     │   └── error/
 *     │       └── index.php
 *     ├── System/                  ← Classes do framework
 *     │   ├── App.php             ← Classe principal
 *     │   ├── Router.php          ← Sistema de rotas
 *     │   ├── Controller.php      ← Classe base dos controllers
 *     │   ├── View.php            ← Sistema de views
 *     │   └── Redirect.php        ← Sistema de redirecionamento
 *     └── Traits/
 *         └── Singleton.php        ← Padrão Singleton
 */


// ============================================================================
// PARTE 3: FLUXO COMPLETO DA APLICAÇÃO (PASSO A PASSO)
// ============================================================================

/**
 * CENÁRIO: Usuário acessa http://localhost/project-task-manager/
 * 
 * Vamos seguir o fluxo completo do código:
 */

// ────────────────────────────────────────────────────────────────────────────
// PASSO 1: index.php (Ponto de entrada)
// ────────────────────────────────────────────────────────────────────────────

// Arquivo: index.php
<?php
declare(strict_types=1);

use App\System\App;

// 1.1: Carrega o autoloader do Composer
require __DIR__ . '/vendor/autoload.php';

// 1.2: Cria uma instância da aplicação (usando Singleton)
$app = App::instance();

// 1.3: Executa a aplicação e exibe o resultado
echo $app->run();

/**
 * EXPLICAÇÃO DO PASSO 1:
 * 
 * 1.1: O autoloader permite usar classes sem precisar fazer require manual
 *      Quando você faz "use App\System\App", o Composer carrega automaticamente
 * 
 * 1.2: App::instance() cria UMA ÚNICA instância da classe App (padrão Singleton)
 *      Singleton garante que só existe uma instância da classe na aplicação
 * 
 * 1.3: $app->run() inicia o processamento da requisição
 *      O resultado (HTML) é exibido com echo
 */


// ────────────────────────────────────────────────────────────────────────────
// PASSO 2: App.php (Classe principal)
// ────────────────────────────────────────────────────────────────────────────

// Arquivo: src/System/App.php
namespace App\System;

use App\Traits\Singleton;

class App
{
    use Singleton;  // Usa o trait Singleton

    public function run(): mixed
    {
        // 2.1: Cria uma instância do Router
        $router = Router::instance();
        
        // 2.2: Executa o router e retorna o resultado
        return $router->run();
    }
}

/**
 * EXPLICAÇÃO DO PASSO 2:
 * 
 * A classe App é simples: ela apenas delega o trabalho para o Router
 * 
 * 2.1: Router::instance() cria uma instância do Router (também Singleton)
 * 2.2: $router->run() processa a rota e retorna o HTML da página
 */


// ────────────────────────────────────────────────────────────────────────────
// PASSO 3: Singleton.php (Padrão Singleton)
// ────────────────────────────────────────────────────────────────────────────

// Arquivo: src/Traits/Singleton.php
namespace App\Traits;

trait Singleton
{
    private static $instance = null;

    // Construtor privado: impede criar instâncias com "new"
    private function __construct()
    {
        $this->init();
    }

    // Método para obter a instância única
    public static function instance(): static
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    // Método init() que as classes podem implementar
    private function init(): void {}
}

/**
 * EXPLICAÇÃO DO PASSO 3:
 * 
 * O padrão Singleton garante que só existe UMA instância da classe.
 * 
 * Como funciona:
 * 
 * 1. Construtor privado: você NÃO pode fazer "new App()"
 * 2. Método instance(): retorna sempre a MESMA instância
 * 3. Na primeira chamada, cria a instância
 * 4. Nas próximas chamadas, retorna a instância já criada
 * 
 * Exemplo:
 * $app1 = App::instance();  // Cria a instância
 * $app2 = App::instance();  // Retorna a mesma instância
 * $app1 === $app2;          // true (são o mesmo objeto)
 */


// ────────────────────────────────────────────────────────────────────────────
// PASSO 4: Router.php (Sistema de rotas)
// ────────────────────────────────────────────────────────────────────────────

// Arquivo: src/System/Router.php
namespace App\System;

use App\Traits\Singleton;

class Router
{
    use Singleton;
    private array $routes = [];

    // 4.1: Método init() é chamado pelo Singleton no construtor
    private function init(): void
    {
        // Carrega o arquivo de rotas
        $this->routes = require getcwd() . '/src/routes.php';
    }

    // 4.2: Método principal que processa a requisição
    public function run(): string|false
    {
        // 4.2.1: Pega o caminho da URL (ex: "/", "/login")
        $path = $this->getCurrentPath();
        
        // 4.2.2: Busca a rota no array de rotas
        $controllerMapping = $this->routes[$path] ?? null;

        // 4.2.3: Se encontrou a rota
        if ($controllerMapping) {
            // Desestrutura o array [Classe, método]
            [$controllerClass, $method] = $controllerMapping;
            
            // Instancia o controller dinamicamente
            $controller = new $controllerClass();
            
            // Chama o método dinamicamente
            return $controller->$method();
        }
        
        // 4.2.4: Se não encontrou, exibe página 404
        return View::render('layouts/404.php');
    }

    // 4.3: Método que extrai o caminho da URL
    public function getCurrentPath()
    {
        // Pega a URL completa
        $fullPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove o prefixo do diretório do projeto
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        
        if ($scriptName !== '/' && str_starts_with($fullPath, $scriptName)) {
            $fullPath = substr($fullPath, strlen($scriptName));
        }
        
        // Garante que sempre comece com /
        return '/' . ltrim($fullPath, '/');
    }
}

/**
 * EXPLICAÇÃO DO PASSO 4:
 * 
 * O Router é o CORAÇÃO do framework. Ele decide qual controller chamar.
 * 
 * 4.1: init() carrega o arquivo routes.php que contém o mapeamento de rotas
 * 
 * 4.2: run() processa a requisição:
 *      - Pega o caminho da URL (ex: "/login")
 *      - Busca no array de rotas
 *      - Se encontrar, instancia o controller e chama o método
 *      - Se não encontrar, exibe página 404
 * 
 * 4.3: getCurrentPath() extrai o caminho limpo da URL
 *      URL: http://localhost/project/login
 *      Retorna: "/login"
 * 
 * EXEMPLO PRÁTICO:
 * 
 * URL acessada: http://localhost/project-task-manager/login
 * 
 * getCurrentPath() retorna: "/login"
 * 
 * Busca em $this->routes["/login"]
 * Encontra: [Login::class, 'index']
 * 
 * Desestrutura:
 * $controllerClass = "App\Controllers\Login"
 * $method = "index"
 * 
 * Instancia:
 * $controller = new App\Controllers\Login();
 * 
 * Chama:
 * return $controller->index();
 */


// ────────────────────────────────────────────────────────────────────────────
// PASSO 5: routes.php (Definição de rotas)
// ────────────────────────────────────────────────────────────────────────────

// Arquivo: src/routes.php
use App\Controllers\Home;
use App\Controllers\Login;

return [
    '/' => [Home::class, 'index'],
    '/login' => [Login::class, 'index'],
    '/login/submit' => [Login::class, 'onLogin'],
];

/**
 * EXPLICAÇÃO DO PASSO 5:
 * 
 * Este arquivo define o MAPEAMENTO entre URLs e Controllers.
 * 
 * Estrutura:
 * 'rota' => [Classe::class, 'método']
 * 
 * Exemplos:
 * 
 * '/' => [Home::class, 'index']
 * Quando acessar "/", chama Home->index()
 * 
 * '/login' => [Login::class, 'index']
 * Quando acessar "/login", chama Login->index()
 * 
 * '/login/submit' => [Login::class, 'onLogin']
 * Quando acessar "/login/submit", chama Login->onLogin()
 * 
 * Home::class é uma constante mágica que retorna "App\Controllers\Home"
 */


// ────────────────────────────────────────────────────────────────────────────
// PASSO 6: Controller.php (Classe base dos controllers)
// ────────────────────────────────────────────────────────────────────────────

// Arquivo: src/System/Controller.php
namespace App\System;

abstract class Controller
{
    protected ?string $controllerName = null;
    private string $controllerViewPath = 'src/Views/';
    
    // 6.1: Construtor define o nome do controller automaticamente
    public function __construct()
    {
        if (!$this->controllerName) {
            // Pega o nome da classe (ex: "Home" de "App\Controllers\Home")
            $this->controllerName = strtolower((new \ReflectionClass($this))->getShortName());
        }
    }
    
    // 6.2: Método index padrão
    public function index(array $data = []): string|false
    {
        // Monta o caminho da view: src/Views/home/index.php
        $viewPath = $this->controllerViewPath . $this->controllerName . '/index.php';
        return $this->render($viewPath, $data);
    }
    
    // 6.3: Método para renderizar views
    protected function render(string $viewPath, array $data = []): string|false
    {
        try {
            return View::render($viewPath, $data);
        } catch (\Throwable $th) {
            return $this->renderError($th);
        }
    }
    
    // 6.4: Método para renderizar erros
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

/**
 * EXPLICAÇÃO DO PASSO 6:
 * 
 * Controller é a classe BASE que todos os controllers herdam.
 * 
 * 6.1: __construct() define automaticamente o nome do controller
 *      Se o controller é "Home", $controllerName = "home"
 * 
 * 6.2: index() é o método padrão
 *      Monta o caminho da view baseado no nome do controller
 *      Controller "Home" → view "src/Views/home/index.php"
 * 
 * 6.3: render() renderiza uma view usando View::render()
 *      Se der erro, chama renderError()
 * 
 * 6.4: renderError() exibe uma página de erro
 * 
 * EXEMPLO:
 * 
 * class Home extends Controller {
 *     // Herda todos os métodos acima
 * }
 * 
 * $home = new Home();
 * $home->index();  // Renderiza src/Views/home/index.php
 */


// ────────────────────────────────────────────────────────────────────────────
// PASSO 7: Home.php (Controller específico)
// ────────────────────────────────────────────────────────────────────────────

// Arquivo: src/Controllers/Home.php
namespace App\Controllers;

use App\System\Controller;

class Home extends Controller
{
    // 7.1: Sobrescreve o método index() se necessário
    public function index(array $data = []): string|false
    {
        // Renderiza o layout específico
        return $this->render('layouts/index.php');
    }
}

/**
 * EXPLICAÇÃO DO PASSO 7:
 * 
 * Home é um CONTROLLER ESPECÍFICO que herda de Controller.
 * 
 * 7.1: Pode sobrescrever métodos da classe pai
 *      Neste caso, renderiza um layout diferente
 * 
 * Se não sobrescrever index(), usa o método da classe pai que
 * renderizaria automaticamente src/Views/home/index.php
 */


// ────────────────────────────────────────────────────────────────────────────
// PASSO 8: View.php (Sistema de views)
// ────────────────────────────────────────────────────────────────────────────

// Arquivo: src/System/View.php
namespace App\System;

class View
{
    public static function render(string $viewFile, array $variables = []): string|false
    {
        // 8.1: Inicia o buffer de saída
        ob_start();
        
        // 8.2: Extrai variáveis para o escopo da view
        extract($variables);
        
        try {
            // 8.3: Monta o caminho completo do arquivo
            $filePath = getcwd() . '/' . $viewFile;
            
            // 8.4: Verifica se o arquivo existe
            if (!file_exists($filePath)) {
                throw new \Exception("FilePath: {$filePath} not exits");
            }
            
            // 8.5: Inclui o arquivo da view
            include $filePath;
            
        } catch (\Throwable $e) {
            throw $e;
        }
        
        // 8.6: Retorna o conteúdo do buffer
        return ob_get_clean();
    }
}

/**
 * EXPLICAÇÃO DO PASSO 8:
 * 
 * View é responsável por RENDERIZAR arquivos PHP (views).
 * 
 * 8.1: ob_start() inicia o buffer de saída
 *      Tudo que for "echo" será capturado no buffer
 * 
 * 8.2: extract($variables) transforma array em variáveis
 *      ['nome' => 'João'] vira $nome = 'João'
 * 
 * 8.3: Monta o caminho completo do arquivo
 *      'layouts/index.php' → 'C:\laragon\www\...\layouts\index.php'
 * 
 * 8.4: Verifica se o arquivo existe
 * 
 * 8.5: include $filePath executa o arquivo PHP
 *      O HTML do arquivo é capturado no buffer
 * 
 * 8.6: ob_get_clean() retorna o conteúdo do buffer e limpa
 * 
 * EXEMPLO:
 * 
 * View::render('layouts/index.php', ['nome' => 'João']);
 * 
 * Dentro de layouts/index.php você pode usar:
 * <h1>Olá, <?= $nome ?></h1>
 * 
 * Resultado: <h1>Olá, João</h1>
 */


// ============================================================================
// PARTE 4: FLUXO VISUAL COMPLETO
// ============================================================================

/**
 * RESUMO DO FLUXO COMPLETO:
 * 
 * 1. Usuário acessa: http://localhost/project-task-manager/
 * 
 * 2. index.php
 *    ↓ require autoload
 *    ↓ $app = App::instance()
 *    ↓ echo $app->run()
 * 
 * 3. App->run()
 *    ↓ $router = Router::instance()
 *    ↓ return $router->run()
 * 
 * 4. Router->run()
 *    ↓ $path = getCurrentPath()  // "/"
 *    ↓ $controllerMapping = $routes["/"]  // [Home::class, 'index']
 *    ↓ [$controllerClass, $method] = $controllerMapping
 *    ↓ $controller = new Home()
 *    ↓ return $controller->index()
 * 
 * 5. Home->index()
 *    ↓ return $this->render('layouts/index.php')
 * 
 * 6. Controller->render()
 *    ↓ return View::render('layouts/index.php', $data)
 * 
 * 7. View::render()
 *    ↓ ob_start()
 *    ↓ extract($variables)
 *    ↓ include 'layouts/index.php'
 *    ↓ return ob_get_clean()
 * 
 * 8. HTML é retornado para index.php
 *    ↓ echo $html
 * 
 * 9. Navegador exibe a página
 */


// ============================================================================
// PARTE 5: EXEMPLO PRÁTICO COMPLETO
// ============================================================================

/**
 * CENÁRIO: Criar uma página de produtos
 * 
 * PASSO 1: Criar a rota
 */

// src/routes.php
return [
    '/' => [Home::class, 'index'],
    '/products' => [Product::class, 'index'],  // ← Nova rota
];

/**
 * PASSO 2: Criar o Model
 */

// src/Models/Product.php
namespace App\Models;

class Product
{
    public function getAll(): array
    {
        // Simulando busca no banco de dados
        return [
            ['id' => 1, 'name' => 'Notebook', 'price' => 3000],
            ['id' => 2, 'name' => 'Mouse', 'price' => 50],
            ['id' => 3, 'name' => 'Teclado', 'price' => 150],
        ];
    }
}

/**
 * PASSO 3: Criar o Controller
 */

// src/Controllers/Product.php
namespace App\Controllers;

use App\System\Controller;
use App\Models\Product as ProductModel;

class Product extends Controller
{
    public function index(): string|false
    {
        // 1. Busca dados no Model
        $productModel = new ProductModel();
        $products = $productModel->getAll();
        
        // 2. Envia dados para a View
        return $this->render('src/Views/product/index.php', [
            'products' => $products
        ]);
    }
}

/**
 * PASSO 4: Criar a View
 */

// src/Views/product/index.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Produtos</title>
</head>
<body>
    <h1>Lista de Produtos</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><?= $product['name'] ?></td>
            <td>R$ <?= $product['price'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
<?php

/**
 * PASSO 5: Acessar no navegador
 * 
 * http://localhost/project-task-manager/products
 * 
 * FLUXO:
 * 1. Router pega "/products"
 * 2. Encontra [Product::class, 'index']
 * 3. Instancia Product controller
 * 4. Chama Product->index()
 * 5. Product->index() busca dados no ProductModel
 * 6. Envia dados para a view
 * 7. View renderiza o HTML com os dados
 * 8. HTML é exibido no navegador
 */


// ============================================================================
// PARTE 6: CONCEITOS IMPORTANTES
// ============================================================================

/**
 * 1. AUTOLOADING:
 *    - Composer carrega classes automaticamente
 *    - Você não precisa fazer require manual
 *    - Basta usar "use" no topo do arquivo
 * 
 * 2. NAMESPACES:
 *    - Organizam classes em "pastas virtuais"
 *    - App\Controllers\Home significa: pasta App/Controllers, classe Home
 *    - Evita conflito de nomes
 * 
 * 3. SINGLETON:
 *    - Garante uma única instância da classe
 *    - Útil para classes que devem existir apenas uma vez (Router, App)
 * 
 * 4. INSTANCIAÇÃO DINÂMICA:
 *    - $class = "MinhaClasse"; new $class();
 *    - Permite criar objetos baseado em strings
 *    - Usado no Router para instanciar controllers
 * 
 * 5. CHAMADA DE MÉTODO DINÂMICA:
 *    - $method = "index"; $obj->$method();
 *    - Permite chamar métodos baseado em strings
 *    - Usado no Router para chamar métodos dos controllers
 * 
 * 6. OUTPUT BUFFERING:
 *    - ob_start() captura tudo que for "echo"
 *    - ob_get_clean() retorna o conteúdo capturado
 *    - Permite manipular HTML antes de enviar ao navegador
 * 
 * 7. EXTRACT:
 *    - Transforma array em variáveis
 *    - ['nome' => 'João'] vira $nome = 'João'
 *    - Útil para passar dados para views
 */


// ============================================================================
// PARTE 7: EXERCÍCIOS PRÁTICOS
// ============================================================================

/**
 * EXERCÍCIO 1: Criar uma página "Sobre"
 * 
 * 1. Adicione a rota em routes.php:
 *    '/about' => [About::class, 'index']
 * 
 * 2. Crie o controller src/Controllers/About.php
 * 
 * 3. Crie a view src/Views/about/index.php
 * 
 * 4. Acesse http://localhost/project-task-manager/about
 */

/**
 * EXERCÍCIO 2: Criar uma página de usuários
 * 
 * 1. Crie o model src/Models/User.php com método getAll()
 * 
 * 2. Crie o controller src/Controllers/User.php
 * 
 * 3. Crie a view src/Views/user/index.php
 * 
 * 4. Adicione a rota '/users' => [User::class, 'index']
 * 
 * 5. Exiba uma lista de usuários na view
 */

/**
 * EXERCÍCIO 3: Adicionar método de detalhes
 * 
 * 1. Adicione a rota '/products/1' => [Product::class, 'show']
 * 
 * 2. Crie o método show($id) no controller Product
 * 
 * 3. Busque o produto específico no model
 * 
 * 4. Crie a view src/Views/product/show.php
 * 
 * 5. Exiba os detalhes do produto
 */


// ============================================================================
// FIM DO GUIA
// ============================================================================

/**
 * PARABÉNS! Agora você entende como um framework MVC funciona!
 * 
 * PRÓXIMOS PASSOS:
 * - Estude sobre banco de dados (PDO)
 * - Aprenda sobre validação de formulários
 * - Entenda sobre autenticação e sessões
 * - Explore sobre middlewares
 * - Aprenda sobre APIs REST
 */
