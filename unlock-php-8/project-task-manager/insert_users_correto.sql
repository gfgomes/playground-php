-- ============================================
-- Script SQL para criar 3 usuários
-- Execute este arquivo no MySQL
-- ============================================

-- Limpa usuários antigos
DELETE FROM `users`;

-- Insere 3 usuários com senha '123' criptografada
-- IMPORTANTE: Estes hashes foram gerados com password_hash('123', PASSWORD_DEFAULT)
-- Cada execução de password_hash gera um hash diferente, mas todos funcionam!

INSERT INTO `users` (`name`, `login`, `password`, `created_at`, `updated_at`, `deleted_at`) VALUES
('João Silva', 'joao', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NULL),
('Maria Santos', 'maria', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NULL),
('Pedro Costa', 'pedro', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NULL);

-- ============================================
-- Credenciais para login:
-- ============================================
-- Login: joao   | Senha: 123
-- Login: maria  | Senha: 123
-- Login: pedro  | Senha: 123

-- ============================================
-- IMPORTANTE:
-- ============================================
-- ❌ NUNCA faça: INSERT INTO users (password) VALUES ('123')
-- ✅ SEMPRE use: INSERT INTO users (password) VALUES ('$2y$10$...')
--
-- Para gerar novos hashes, use PHP:
-- <?php echo password_hash('123', PASSWORD_DEFAULT); ?>
-- ============================================
