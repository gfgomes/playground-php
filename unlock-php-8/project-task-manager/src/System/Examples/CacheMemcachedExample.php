<?php

namespace App\System\Examples;

/**
 * Exemplo de Cache com Memcached
 * 
 * Memcached é mais simples que Redis:
 * - Apenas strings (precisa serializar arrays/objetos)
 * - Sem persistência (perde tudo ao reiniciar)
 * - Mais leve e rápido para casos simples
 * - Não tem bancos separados (usa prefixos)
 * 
 * Quando usar Memcached:
 * - Cache simples de dados
 * - Performance máxima
 * - Não precisa de persistência
 * - Não precisa de estruturas complexas
 */
class CacheMemcachedExample
{
    private static ?\Memcache $instance = null;
    private static ?int $version = null;
    private static string $prefix = 'task_manager:'; // Prefixo do projeto

    /**
     * Conecta ao Memcached
     * 
     * @return \Memcache
     */
    private static function connect(): \Memcache
    {
        if (self::$instance === null) {
            self::$instance = new \Memcache();
            
            // Conecta ao servidor Memcached (localhost:11211)
            // 11211 é a porta padrão do Memcached
            self::$instance->connect('localhost', 11211) 
                or die("Não foi possível conectar ao Memcached");
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
            $version = self::connect()->get(self::$prefix . 'cache:version');
            self::$version = $version ? (int)$version : 1;
        }
        return self::$version;
    }

    /**
     * Gera chave com prefixo e versão
     * Exemplo: v1:task_manager:users
     * 
     * Importante: Memcached não tem bancos separados como Redis,
     * então SEMPRE use prefixos para separar projetos!
     * 
     * @param string $key
     * @return string
     */
    private static function key(string $key): string
    {
        return 'v' . self::getVersion() . ':' . self::$prefix . $key;
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
        // Parâmetros do Memcache::set():
        // 1. Chave
        // 2. Valor (serializado para guardar arrays/objetos)
        // 3. Flag (0 = sem compressão, MEMCACHE_COMPRESSED = com compressão)
        // 4. TTL em segundos
        return self::connect()->set(
            self::key($key),
            serialize($value),
            0, // Sem compressão (use MEMCACHE_COMPRESSED se quiser)
            $ttl
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
        
        // Memcache retorna false se não existir
        if ($value === false) {
            return false;
        }
        
        return unserialize($value);
    }

    /**
     * Deleta uma chave específica
     * 
     * @param string $key
     * @return bool
     */
    public static function delete(string $key): bool
    {
        return self::connect()->delete(self::key($key));
    }

    /**
     * Verifica se chave existe
     * 
     * Nota: Memcache não tem método exists(),
     * então fazemos get() e verificamos se é false
     * 
     * @param string $key
     * @return bool
     */
    public static function exists(string $key): bool
    {
        return self::connect()->get(self::key($key)) !== false;
    }

    /**
     * Limpa TODO o cache (TODOS os projetos!)
     * 
     * CUIDADO: flush() limpa TUDO do Memcached,
     * incluindo cache de outros projetos!
     * 
     * @return bool
     */
    public static function flush(): bool
    {
        return self::connect()->flush();
    }

    /**
     * Invalida TODO o cache do projeto instantaneamente
     * Muda a versão, tornando todas as chaves antigas "invisíveis"
     * 
     * Exemplo:
     * - Versão 1: v1:task_manager:users
     * - Após invalidateAll(): Versão 2
     * - Busca agora: v2:task_manager:users (não existe!)
     * 
     * Vantagem: Não precisa deletar milhares de chaves
     * 
     * @return void
     */
    public static function invalidateAll(): void
    {
        $newVersion = self::getVersion() + 1;
        self::connect()->set(
            self::$prefix . 'cache:version',
            $newVersion,
            0,
            0 // Sem expiração
        );
        self::$version = $newVersion;
    }

    /**
     * Incrementa contador
     * 
     * Nota: Memcache tem increment(), mas não é tão robusto quanto Redis
     * 
     * @param string $key
     * @param int $value Quanto incrementar (padrão: 1)
     * @return int|false Novo valor ou false se falhar
     */
    public static function increment(string $key, int $value = 1): int|false
    {
        return self::connect()->increment(self::key($key), $value);
    }

    /**
     * Decrementa contador
     * 
     * @param string $key
     * @param int $value Quanto decrementar (padrão: 1)
     * @return int|false Novo valor ou false se falhar
     */
    public static function decrement(string $key, int $value = 1): int|false
    {
        return self::connect()->decrement(self::key($key), $value);
    }

    /**
     * Obtém estatísticas do Memcached
     * 
     * @return array|false
     */
    public static function getStats(): array|false
    {
        return self::connect()->getStats();
    }

    /**
     * Fecha conexão
     * 
     * @return bool
     */
    public static function close(): bool
    {
        if (self::$instance !== null) {
            return self::$instance->close();
        }
        return true;
    }
}

// ============================================
// EXEMPLOS DE USO
// ============================================

/*

// 1. Salvar dados
CacheMemcachedExample::set('users', ['João', 'Maria'], 3600); // 1 hora
CacheMemcachedExample::set('config', ['theme' => 'dark'], 86400); // 24 horas

// 2. Buscar dados (padrão Cache-Aside)
$users = CacheMemcachedExample::get('users');
if ($users === false) {
    // Não está no cache, busca do banco
    $users = $db->query("SELECT * FROM users");
    
    // Salva no cache
    CacheMemcachedExample::set('users', $users, 3600);
}

// 3. Deletar chave específica
CacheMemcachedExample::delete('users');

// 4. Verificar existência
if (CacheMemcachedExample::exists('users')) {
    echo "Existe no cache!";
}

// 5. Contador (views de página)
// Primeiro, inicializa o contador
CacheMemcachedExample::set('page:views', 0, 0); // Sem expiração
// Depois incrementa
CacheMemcachedExample::increment('page:views'); // 1, 2, 3...

// 6. Invalidar tudo do projeto (após deploy)
CacheMemcachedExample::invalidateAll();

// 7. Ver estatísticas
$stats = CacheMemcachedExample::getStats();
print_r($stats);

// 8. Limpar tudo (CUIDADO: limpa TODOS os projetos!)
// CacheMemcachedExample::flush();

// 9. Fechar conexão (opcional, PHP fecha automaticamente)
CacheMemcachedExample::close();

*/

// ============================================
// COMPARAÇÃO: Memcached vs Redis
// ============================================

/*

MEMCACHED:
✅ Mais simples
✅ Mais leve
✅ Rápido para cache simples
❌ Só strings (precisa serializar)
❌ Sem persistência
❌ Sem bancos separados (usa prefixos)
❌ Menos recursos

REDIS:
✅ Mais recursos (hash, list, set, etc)
✅ Persistência (salva em disco)
✅ Bancos separados (0-15)
✅ Pub/Sub
✅ Transações
❌ Mais complexo
❌ Usa mais memória

QUANDO USAR MEMCACHED:
- Cache simples de dados
- Não precisa de persistência
- Quer máxima simplicidade

QUANDO USAR REDIS:
- Precisa de persistência
- Precisa de estruturas complexas
- Múltiplos projetos
- Pub/Sub, filas, etc

*/
