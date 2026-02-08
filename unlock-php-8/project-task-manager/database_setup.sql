-- Script para criar a base de dados do projeto PHP Book
-- Data de criação: 08/02/2026

-- Criar a base de dados
CREATE DATABASE IF NOT EXISTS php_book;

-- Usar a base de dados criada
USE php_book;

-- Criar tabela de usuários
CREATE TABLE `php_book`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100),
  `login` VARCHAR(100),
  `password` VARCHAR(200),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_login` (`login`)
);

-- Criar tabela de tarefas
CREATE TABLE `php_book`.`tasks` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11),
  `title` VARCHAR(200),
  `is_concluded` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `php_book`.`users` (`id`) ON DELETE CASCADE
);

-- Inserir alguns dados de exemplo (opcional)
-- INSERT INTO `php_book`.`users` (`name`, `login`, `password`) VALUES 
-- ('Admin', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
-- ('João Silva', 'joao.silva', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
-- ('Maria Santos', 'maria.santos', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- INSERT INTO `php_book`.`tasks` (`user_id`, `title`, `is_concluded`) VALUES
-- (1, 'Configurar projeto PHP', 1),
-- (1, 'Implementar sistema de login', 0),
-- (2, 'Criar documentação', 0),
-- (3, 'Testar funcionalidades', 0);