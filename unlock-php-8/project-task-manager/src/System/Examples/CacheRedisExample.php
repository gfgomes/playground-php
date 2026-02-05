<?php

namespace App\System\Examples;

/**
 * Exemplo de Cache com Redis
 * 
 * Redis é mais poderoso que Memcached:
 * - Suporta múltiplos tipos de dados (string, list, set, hash)
 * - Tem persistência (salva em disco)
 * - Suporta transações
 * - Tem pub/sub
 * 
 * Quando usar Redis:
 * - Aplicações complexas
 * - Precisa de persistência
 * - Precisa de estruturas de dados avançadas
 * - Múltiplos projetos (usa bancos 0-15 ou prefixos)
 */
class CacheRedisExample
{
    private static ?\Redis $instance = null;
    private static ?int $version = null;

    /**
     * Conecta ao Redis
     * 
     * @return \Redis
     */
    private static function connect(): \Redis
    {
        if (self::$instance === null) {
            self::$instance = new \Redis();
            
            // Conecta ao servidor Redis (localhost:6379)
            self::$instance->connect('localhost', 6379);
            
            // Seleciona banco 0 (Redis tem 16 bancos: 0-15)
            // Use bancos diferentes para projetos diferentes
            self::$instance->select(0);
        }
        return self::$instance;
    }

    /**
     * Busca versão atual do cache
     * Usado para invalidação em massa (versionamento)
     * 
     * @return int
     */
    private static function getVersion(): int
    {
        if (self::$version === null) {
            $version = self::connect()->get('cache:version');
            self::$version = $version ? (int)$version : 1;
        }
        return self::$version;
    }

    /**
     * Gera chave com prefixo e versão
     * Exemplo: v1:task_manager:users
     * 
     * @param string $key
     * @return string
     */
    private static function key(string $key): string
    {
        return 'v' . self::getVersion() . ':task_manager:' . $key;
    }

    /**
     * Salva valor no cache
     * 
     * @param string $key Chave
     * @param mixed $value Valor (será serializado)
     * @param int $ttl Tempo de vida em segundos (padrão: 1 hora)
     * @return bool
     */
    public static function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        // setex = SET com EXpiration
        return self::connect()->setex(
            self::key($key),
            $ttl,
            serialize($value) // Serializa para guardar arrays/objetos
        );
    }

    /**
     * Busca valor do cache
     * 
     * @param string $key
     * @return mixed Retorna false se não existir
     */
    public static function get(string $key): mixed
    {
        $value = self::connect()->get(self::key($key));
        return $value ? unserialize($value) : false;
    }

    /**
     * Deleta uma chave específica
     * 
     * @param string $key
     * @return int Número de chaves deletadas
     */
    public static function delete(string $key): int
    {
        return self::connect()->del(self::key($key));
    }

    /**
     * Verifica se chave existe
     * 
     * @param string $key
     * @return bool
     */
    public static function exists(string $key): bool
    {
        return self::connect()->exists(self::key($key)) > 0;
    }

    /**
     * Limpa TODO o banco atual (cuidado!)
     * 
     * @return bool
     */
    public static function flush(): bool
    {
        return self::connect()->flushDB();
    }

    /**
     * Invalida TODO o cache instantaneamente
     * Muda a versão, tornando todas as chaves antigas "invisíveis"
     * 
     * Exemplo:
     * - Versão 1: v1:task_manager:users
     * - Após invalidateAll(): Versão 2
     * - Busca agora: v2:task_manager:users (não existe!)
     * 
     * @return void
     */
    public static function invalidateAll(): void
    {
        $newVersion = self::getVersion() + 1;
        self::connect()->set('cache:version', $newVersion);
        self::$version = $newVersion;
    }

    /**
     * Incrementa contador (atômico, thread-safe)
     * 
     * @param string $key
     * @return int Novo valor
     */
    public static function increment(string $key): int
    {
        return self::connect()->incr(self::key($key));
    }

    /**
     * Decrementa contador
     * 
     * @param string $key
     * @return int Novo valor
     */
    public static function decrement(string $key): int
    {
        return self::connect()->decr(self::key($key));
    }
}

// ============================================
// EXEMPLOS DE USO
// ============================================

/*

// 1. Salvar dados
CacheRedisExample::set('users', ['João', 'Maria'], 3600); // 1 hora
CacheRedisExample::set('config', ['theme' => 'dark'], 86400); // 24 horas

// 2. Buscar dados
$users = CacheRedisExample::get('users');
if ($users === false) {
    // Não está no cache, busca do banco
    $users = $db->query("SELECT * FROM users");
    CacheRedisExample::set('users', $users, 3600);
}

// 3. Deletar
CacheRedisExample::delete('users');

// 4. Verificar existência
if (CacheRedisExample::exists('users')) {
    echo "Existe no cache!";
}

// 5. Contador (views de página)
CacheRedisExample::increment('page:views'); // 1, 2, 3...

// 6. Invalidar tudo (após deploy)
CacheRedisExample::invalidateAll();

// 7. Limpar tudo (cuidado!)
CacheRedisExample::flush();

*/
