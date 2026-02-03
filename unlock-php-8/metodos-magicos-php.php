<?php

namespace App\Controllers;

class ExemploMetodosMagicos
{
    // ========================================
    // 1. CONSTANTES MÁGICAS (retornam strings)
    // ========================================

    public function exemploConstantesMagicas()
    {
        // ::class - Nome completo da classe (PHP 5.5+)
        echo Home::class;  // "App\Controllers\Home"
        echo self::class;  // "App\Controllers\ExemploMetodosMagicos"

        // __CLASS__ - Nome completo da classe atual
        echo __CLASS__;  // "App\Controllers\ExemploMetodosMagicos"

        // __METHOD__ - Nome completo do método (Classe::metodo)
        echo __METHOD__;  // "App\Controllers\ExemploMetodosMagicos::exemploConstantesMagicas"

        // __FUNCTION__ - Nome da função/método atual
        echo __FUNCTION__;  // "exemploConstantesMagicas"

        // __NAMESPACE__ - Namespace atual
        echo __NAMESPACE__;  // "App\Controllers"

        // __FILE__ - Caminho completo do arquivo
        echo __FILE__;  // "C:\laragon\www\...\metodos-magicos-php.php"

        // __DIR__ - Diretório do arquivo
        echo __DIR__;  // "C:\laragon\www\...\unlock-php-8"

        // __LINE__ - Número da linha atual
        echo __LINE__;  // 35 (exemplo)

        // __TRAIT__ - Nome do trait (só funciona dentro de traits)
        // echo __TRAIT__;  // "" (vazio se não estiver em trait)
    }

    // ========================================
    // 2. MÉTODOS MÁGICOS (comportamentos especiais)
    // ========================================

    private $data = [];

    // __construct() - Chamado ao criar objeto
    public function __construct()
    {
        echo "Objeto criado!\n";
    }

    // __destruct() - Chamado ao destruir objeto
    public function __destruct()
    {
        echo "Objeto destruído!\n";
    }

    // __get() - Chamado ao acessar propriedade inexistente
    public function __get($name)
    {
        echo "Tentou acessar: $name\n";
        return $this->data[$name] ?? null;
    }

    // __set() - Chamado ao definir propriedade inexistente
    public function __set($name, $value)
    {
        echo "Definindo $name = $value\n";
        $this->data[$name] = $value;
    }

    // __isset() - Chamado ao usar isset() em propriedade inexistente
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    // __unset() - Chamado ao usar unset() em propriedade inexistente
    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    // __call() - Chamado ao chamar método inexistente
    public function __call($name, $arguments)
    {
        echo "Método $name não existe. Argumentos: " . implode(', ', $arguments) . "\n";
    }

    // __callStatic() - Chamado ao chamar método estático inexistente
    public static function __callStatic($name, $arguments)
    {
        echo "Método estático $name não existe\n";
    }

    // __toString() - Chamado ao converter objeto para string
    public function __toString()
    {
        return "Objeto ExemploMetodosMagicos";
    }

    // __invoke() - Chamado ao usar objeto como função
    public function __invoke($param)
    {
        echo "Objeto chamado como função com: $param\n";
    }

    // __clone() - Chamado ao clonar objeto
    public function __clone()
    {
        echo "Objeto clonado!\n";
    }

    // __sleep() - Chamado antes de serialize()
    public function __sleep()
    {
        echo "Preparando para serializar\n";
        return ['data'];  // Propriedades a serializar
    }

    // __wakeup() - Chamado depois de unserialize()
    public function __wakeup()
    {
        echo "Objeto desserializado\n";
    }

    // __serialize() - Alternativa moderna ao __sleep() (PHP 7.4+)
    public function __serialize(): array
    {
        return ['data' => $this->data];
    }

    // __unserialize() - Alternativa moderna ao __wakeup() (PHP 7.4+)
    public function __unserialize(array $data): void
    {
        $this->data = $data['data'];
    }

    // __set_state() - Chamado por var_export()
    public static function __set_state($array)
    {
        $obj = new self();
        $obj->data = $array['data'];
        return $obj;
    }

    // __debugInfo() - Controla o que var_dump() mostra
    public function __debugInfo()
    {
        return [
            'data' => $this->data,
            'info' => 'Informação customizada'
        ];
    }
}

// ========================================
// EXEMPLOS DE USO
// ========================================

echo "=== CONSTANTES MÁGICAS ===\n";
echo "Classe: " . ExemploMetodosMagicos::class . "\n";
echo "Arquivo: " . __FILE__ . "\n";
echo "Diretório: " . __DIR__ . "\n";
echo "Linha: " . __LINE__ . "\n";
echo "Namespace: " . __NAMESPACE__ . "\n\n";

echo "=== MÉTODOS MÁGICOS ===\n";
$obj = new ExemploMetodosMagicos();  // __construct

// __get e __set
$obj->nome = "João";  // __set
echo $obj->nome . "\n";  // __get

// __isset e __unset
var_dump(isset($obj->nome));  // __isset: true
unset($obj->nome);  // __unset
var_dump(isset($obj->nome));  // __isset: false

// __call
$obj->metodoInexistente('arg1', 'arg2');  // __call

// __callStatic
ExemploMetodosMagicos::metodoEstaticoInexistente();  // __callStatic

// __toString
echo $obj . "\n";  // __toString

// __invoke
$obj('parametro');  // __invoke

// __clone
$clone = clone $obj;  // __clone

// __debugInfo
var_dump($obj);  // __debugInfo

// __destruct é chamado automaticamente no final


// ========================================
// CASOS DE USO PRÁTICOS
// ========================================

// 1. ::class em rotas (seu caso)
$routes = [
    '/' => [ExemploMetodosMagicos::class, 'index'],
];

// 2. __get/__set para propriedades dinâmicas
class Config
{
    private $settings = [];
    
    public function __get($key) {
        return $this->settings[$key] ?? null;
    }
    
    public function __set($key, $value) {
        $this->settings[$key] = $value;
    }
}

$config = new Config();
$config->database = 'mysql';  // Usa __set
echo $config->database;  // Usa __get

// 3. __toString para debug
class User
{
    public function __construct(public string $name) {}
    
    public function __toString() {
        return "User: {$this->name}";
    }
}

$user = new User('Maria');
echo $user;  // "User: Maria"

// 4. __invoke para objetos callable
class Logger
{
    public function __invoke($message) {
        echo "[LOG] $message\n";
    }
}

$log = new Logger();
$log('Erro no sistema');  // Usa como função

// 5. __call para método fluente
class QueryBuilder
{
    private $query = [];
    
    public function __call($method, $args) {
        $this->query[] = strtoupper($method) . ' ' . implode(', ', $args);
        return $this;  // Permite encadeamento
    }
    
    public function get() {
        return implode(' ', $this->query);
    }
}

$query = new QueryBuilder();
echo $query->select('*')->from('users')->where('id = 1')->get();
// "SELECT * FROM users WHERE id = 1"
