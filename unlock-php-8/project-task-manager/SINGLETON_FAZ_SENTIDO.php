<?php
/**
 * ============================================================================
 * SINGLETON EM PHP: FAZ SENTIDO OU NÃO?
 * ============================================================================
 * 
 * Você está CERTO em questionar! Vamos entender quando Singleton é útil
 * e quando é apenas "cargo cult programming" (copiar sem entender).
 */


// ============================================================================
// PARTE 1: VOCÊ TEM RAZÃO - PHP É STATELESS
// ============================================================================

/**
 * FATO: PHP é stateless (sem estado entre requisições)
 * 
 * Cada requisição HTTP:
 * 1. Inicia do zero
 * 2. Carrega o código
 * 3. Executa
 * 4. Morre
 * 5. Memória é liberada
 * 
 * Na PRÓXIMA requisição:
 * - Tudo começa de novo
 * - Não há memória compartilhada entre requisições
 * - Singleton de uma requisição NÃO existe na próxima
 * 
 * ENTÃO POR QUE USAR SINGLETON?
 */


// ============================================================================
// PARTE 2: QUANDO SINGLETON FAZ SENTIDO EM PHP
// ============================================================================

/**
 * Singleton em PHP NÃO é sobre "compartilhar entre requisições"
 * É sobre "compartilhar DENTRO de uma única requisição"
 * 
 * CENÁRIOS ONDE FAZ SENTIDO:
 */

// ────────────────────────────────────────────────────────────────────────────
// CENÁRIO 1: Evitar múltiplas conexões com banco de dados
// ────────────────────────────────────────────────────────────────────────────

// ❌ SEM SINGLETON - Problema:
class Database
{
    private $connection;
    
    public function __construct()
    {
        // Cria uma NOVA conexão toda vez
        $this->connection = new PDO('mysql:host=localhost;dbname=test', 'user', 'pass');
        echo "Nova conexão criada!\n";
    }
}

// Em uma única requisição:
$db1 = new Database();  // "Nova conexão criada!"
$db2 = new Database();  // "Nova conexão criada!" ← DESPERDÍCIO!
$db3 = new Database();  // "Nova conexão criada!" ← DESPERDÍCIO!

// Resultado: 3 conexões abertas desnecessariamente
// Problema: Limite de conexões do MySQL pode ser atingido
// Performance: Cada conexão consome recursos


// ✅ COM SINGLETON - Solução:
class DatabaseSingleton
{
    private static $instance = null;
    private $connection;
    
    private function __construct()
    {
        $this->connection = new PDO('mysql:host=localhost;dbname=test', 'user', 'pass');
        echo "Nova conexão criada!\n";
    }
    
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

// Em uma única requisição:
$db1 = DatabaseSingleton::instance();  // "Nova conexão criada!"
$db2 = DatabaseSingleton::instance();  // (nada) ← Reutiliza!
$db3 = DatabaseSingleton::instance();  // (nada) ← Reutiliza!

// Resultado: 1 conexão compartilhada
// Benefício: Economia de recursos


// ────────────────────────────────────────────────────────────────────────────
// CENÁRIO 2: Carregar configurações pesadas apenas uma vez
// ────────────────────────────────────────────────────────────────────────────

// ❌ SEM SINGLETON - Problema:
class Config
{
    private $settings;
    
    public function __construct()
    {
        // Lê arquivo grande toda vez
        $this->settings = json_decode(file_get_contents('config.json'), true);
        echo "Arquivo carregado!\n";
    }
}

// Em uma única requisição (vários controllers):
$config1 = new Config();  // "Arquivo carregado!" (lê disco)
$config2 = new Config();  // "Arquivo carregado!" (lê disco) ← LENTO!
$config3 = new Config();  // "Arquivo carregado!" (lê disco) ← LENTO!

// Problema: Lê o mesmo arquivo 3 vezes do disco


// ✅ COM SINGLETON - Solução:
class ConfigSingleton
{
    private static $instance = null;
    private $settings;
    
    private function __construct()
    {
        $this->settings = json_decode(file_get_contents('config.json'), true);
        echo "Arquivo carregado!\n";
    }
    
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

// Em uma única requisição:
$config1 = ConfigSingleton::instance();  // "Arquivo carregado!" (lê disco)
$config2 = ConfigSingleton::instance();  // (nada) ← Usa cache em memória!
$config3 = ConfigSingleton::instance();  // (nada) ← Usa cache em memória!

// Benefício: Lê o arquivo apenas 1 vez


// ────────────────────────────────────────────────────────────────────────────
// CENÁRIO 3: Router com rotas carregadas
// ────────────────────────────────────────────────────────────────────────────

// ❌ SEM SINGLETON - Problema:
class Router
{
    private $routes = [];
    
    public function __construct()
    {
        // Carrega rotas toda vez
        $this->routes = require 'routes.php';
        echo "Rotas carregadas!\n";
    }
}

// Em uma única requisição (se você instanciar várias vezes):
$router1 = new Router();  // "Rotas carregadas!"
$router2 = new Router();  // "Rotas carregadas!" ← Desnecessário!

// Problema: Carrega o mesmo arquivo múltiplas vezes


// ✅ COM SINGLETON - Solução:
class RouterSingleton
{
    private static $instance = null;
    private $routes = [];
    
    private function __construct()
    {
        $this->routes = require 'routes.php';
        echo "Rotas carregadas!\n";
    }
    
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

// Em uma única requisição:
$router1 = RouterSingleton::instance();  // "Rotas carregadas!"
$router2 = RouterSingleton::instance();  // (nada) ← Reutiliza!

// Benefício: Carrega rotas apenas 1 vez


// ============================================================================
// PARTE 3: QUANDO SINGLETON NÃO FAZ SENTIDO
// ============================================================================

/**
 * CASOS ONDE SINGLETON É DESNECESSÁRIO OU PREJUDICIAL:
 */

// ────────────────────────────────────────────────────────────────────────────
// CASO 1: Classes que você instancia apenas UMA VEZ naturalmente
// ────────────────────────────────────────────────────────────────────────────

// ❌ SINGLETON DESNECESSÁRIO:
class App
{
    use Singleton;
    
    public function run()
    {
        // ...
    }
}

$app = App::instance();
$app->run();

// Por que é desnecessário?
// Você só instancia App UMA VEZ no index.php
// Não há risco de criar múltiplas instâncias acidentalmente


// ✅ ALTERNATIVA SIMPLES:
class AppSimples
{
    public function run()
    {
        // ...
    }
}

$app = new AppSimples();  // Simples e direto
$app->run();

// Benefício: Código mais simples, sem complexidade desnecessária


// ────────────────────────────────────────────────────────────────────────────
// CASO 2: Controllers (cada requisição precisa de uma instância nova)
// ────────────────────────────────────────────────────────────────────────────

// ❌ SINGLETON ERRADO:
class UserController
{
    use Singleton;
    private $userId;
    
    public function show($id)
    {
        $this->userId = $id;
        // ...
    }
}

// Problema: Se você precisar processar múltiplos usuários na mesma requisição:
$controller = UserController::instance();
$controller->show(1);  // userId = 1
$controller->show(2);  // userId = 2 ← Sobrescreve o anterior!

// Singleton aqui é PREJUDICIAL!


// ✅ SEM SINGLETON:
class UserControllerCorreto
{
    private $userId;
    
    public function show($id)
    {
        $this->userId = $id;
        // ...
    }
}

// Cada instância é independente:
$controller1 = new UserControllerCorreto();
$controller1->show(1);  // userId = 1

$controller2 = new UserControllerCorreto();
$controller2->show(2);  // userId = 2

// Ambos coexistem sem conflito


// ────────────────────────────────────────────────────────────────────────────
// CASO 3: Classes que precisam de parâmetros no construtor
// ────────────────────────────────────────────────────────────────────────────

// ❌ SINGLETON COMPLICADO:
class Logger
{
    use Singleton;
    private $logFile;
    
    private function __construct()
    {
        // Como passar parâmetros? Complicado!
        $this->logFile = 'default.log';
    }
}

// Problema: Não consegue passar parâmetros facilmente


// ✅ SEM SINGLETON:
class LoggerSimples
{
    private $logFile;
    
    public function __construct($logFile)
    {
        $this->logFile = $logFile;
    }
}

$logger = new LoggerSimples('app.log');  // Flexível!


// ============================================================================
// PARTE 4: NO SEU FRAMEWORK - ANÁLISE CRÍTICA
// ============================================================================

/**
 * Vamos analisar se Singleton faz sentido em cada classe:
 */

// ────────────────────────────────────────────────────────────────────────────
// App::instance() - DESNECESSÁRIO
// ────────────────────────────────────────────────────────────────────────────

// Uso atual:
$app = App::instance();
$app->run();

// Você só chama isso UMA VEZ no index.php
// Não há risco de criar múltiplas instâncias

// ALTERNATIVA MAIS SIMPLES:
$app = new App();
$app->run();

// Conclusão: Singleton aqui é OVER-ENGINEERING (complexidade desnecessária)


// ────────────────────────────────────────────────────────────────────────────
// Router::instance() - FAZ SENTIDO (mas com ressalvas)
// ────────────────────────────────────────────────────────────────────────────

// Uso atual:
$router = Router::instance();  // Carrega rotas no init()
$router->run();

// Benefício: Se você chamar Router::instance() em vários lugares,
// as rotas são carregadas apenas 1 vez

// MAS: No seu código, você só chama Router::instance() UMA VEZ em App->run()
// Então o benefício é MÍNIMO

// ALTERNATIVA:
$router = new Router();  // Carrega rotas no construtor
$router->run();

// Conclusão: Singleton aqui é OPCIONAL, não essencial


// ============================================================================
// PARTE 5: QUANDO REALMENTE PRECISARIA DE SINGLETON
// ============================================================================

/**
 * Exemplos REAIS onde Singleton faz diferença:
 */

// ────────────────────────────────────────────────────────────────────────────
// EXEMPLO 1: Logger usado em múltiplos lugares
// ────────────────────────────────────────────────────────────────────────────

class Logger
{
    use Singleton;
    private $file;
    
    private function __construct()
    {
        $this->file = fopen('app.log', 'a');
    }
    
    public function log($message)
    {
        fwrite($this->file, date('Y-m-d H:i:s') . " - $message\n");
    }
}

// Em vários lugares do código:
Logger::instance()->log('User logged in');      // Abre arquivo 1 vez
Logger::instance()->log('Product created');     // Reutiliza
Logger::instance()->log('Order processed');     // Reutiliza

// Benefício: Arquivo aberto apenas 1 vez, múltiplas escritas


// ────────────────────────────────────────────────────────────────────────────
// EXEMPLO 2: Cache em memória
// ────────────────────────────────────────────────────────────────────────────

class Cache
{
    use Singleton;
    private $data = [];
    
    public function get($key)
    {
        return $this->data[$key] ?? null;
    }
    
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }
}

// Em vários lugares:
Cache::instance()->set('user_1', $userData);    // Armazena
// ... mais tarde ...
$user = Cache::instance()->get('user_1');       // Recupera do MESMO array

// Benefício: Cache compartilhado entre diferentes partes do código


// ────────────────────────────────────────────────────────────────────────────
// EXEMPLO 3: Conexão com banco de dados
// ────────────────────────────────────────────────────────────────────────────

class DB
{
    use Singleton;
    private $pdo;
    
    private function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=app', 'user', 'pass');
    }
    
    public function query($sql)
    {
        return $this->pdo->query($sql);
    }
}

// Em múltiplos models:
class UserModel
{
    public function find($id)
    {
        return DB::instance()->query("SELECT * FROM users WHERE id = $id");
    }
}

class ProductModel
{
    public function find($id)
    {
        return DB::instance()->query("SELECT * FROM products WHERE id = $id");
    }
}

// Benefício: UMA conexão compartilhada entre todos os models


// ============================================================================
// PARTE 6: CONCLUSÃO E RECOMENDAÇÕES
// ============================================================================

/**
 * RESUMO:
 * 
 * 1. SINGLETON EM PHP FAZ SENTIDO QUANDO:
 *    ✅ Recurso caro (conexão DB, arquivo, socket)
 *    ✅ Usado em múltiplos lugares na mesma requisição
 *    ✅ Precisa compartilhar estado (cache, configuração)
 * 
 * 2. SINGLETON NÃO FAZ SENTIDO QUANDO:
 *    ❌ Classe instanciada apenas 1 vez naturalmente
 *    ❌ Cada uso precisa de instância independente
 *    ❌ Precisa de parâmetros no construtor
 * 
 * 3. NO SEU FRAMEWORK:
 *    - App::instance() → DESNECESSÁRIO (over-engineering)
 *    - Router::instance() → OPCIONAL (benefício mínimo)
 *    - Melhor usar Singleton em: DB, Cache, Logger, Config
 * 
 * 4. ALTERNATIVA MODERNA:
 *    - Usar Dependency Injection Container
 *    - Frameworks modernos (Laravel, Symfony) usam DI em vez de Singleton
 *    - Mais flexível e testável
 */


// ============================================================================
// PARTE 7: REFATORAÇÃO SUGERIDA
// ============================================================================

/**
 * Como seu framework poderia ser SEM Singleton:
 */

// index.php
$app = new App();
echo $app->run();

// App.php
class App
{
    public function run(): mixed
    {
        $router = new Router();  // Simples!
        return $router->run();
    }
}

// Router.php
class Router
{
    private array $routes = [];
    
    public function __construct()
    {
        $this->routes = require getcwd() . '/src/routes.php';
    }
    
    public function run(): string|false
    {
        // ... mesmo código
    }
}

/**
 * Resultado:
 * - Código mais simples
 * - Mais fácil de testar
 * - Mais fácil de entender
 * - Funciona exatamente igual
 * 
 * O Singleton aqui é "cargo cult" - copiado de frameworks maiores
 * onde faz mais sentido, mas desnecessário em um framework simples.
 */


// ============================================================================
// RESPOSTA FINAL À SUA PERGUNTA
// ============================================================================

/**
 * "Faz sentido ter Singleton em PHP já que toda requisição nasce e morre?"
 * 
 * RESPOSTA: Depende!
 * 
 * - Singleton NÃO é sobre compartilhar entre requisições (isso é impossível)
 * - Singleton É sobre compartilhar DENTRO de uma requisição
 * - Faz sentido quando você tem recursos caros usados múltiplas vezes
 * - NO SEU CASO: é desnecessário, pois você instancia App e Router apenas 1 vez
 * 
 * "Em que casos teria mais de uma instância?"
 * 
 * EXEMPLOS:
 * - DB::instance() chamado em 10 models diferentes
 * - Logger::instance() chamado em 50 lugares do código
 * - Cache::instance() usado em controllers, models, services
 * 
 * Sem Singleton: 10 conexões DB, 50 arquivos abertos, 50 arrays de cache
 * Com Singleton: 1 conexão DB, 1 arquivo aberto, 1 array de cache
 * 
 * CONCLUSÃO: Você está certo em questionar! No seu framework simples,
 * Singleton é over-engineering. Use apenas onde realmente faz diferença.
 */
