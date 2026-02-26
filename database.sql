-- 1. Criar o Banco de Dados
CREATE DATABASE IF NOT EXISTS watchlist_db
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE watchlist_db;

-- 2. Criar a Tabela de Usuarios Primeiro (Pai)
CREATE TABLE IF NOT EXISTS users
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- 3. Criar a Tabela Watchlist (Filha) com suporte a multiplos usuarios
CREATE TABLE IF NOT EXISTS watchlist
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT                  NOT NULL, -- Relacionado ao ID da tabela users
    tmdb_id     INT                  NOT NULL,
    type        ENUM ('movie', 'tv') NOT NULL                                          DEFAULT 'movie',
    title       VARCHAR(255)         NOT NULL,
    poster_path VARCHAR(500),
    status      ENUM ('nao_assistido', 'assistindo', 'assistido', 'pretendo_assistir') DEFAULT 'nao_assistido',
    rating      INT CHECK (rating IS NULL OR (rating >= 1 AND rating <= 5)),
    added_at    TIMESTAMP                                                              DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP                                                              DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,


    -- Bloqueia apenas se o MESMO usuario tentar adicionar o MESMO filme/serie duas vezes.
    UNIQUE KEY unique_user_item (user_id, tmdb_id, type),

    -- Indices para performance
    INDEX idx_status (status),
    INDEX idx_user (user_id),

    -- Chave Estrangeira (Opcional, mas recomendada para integridade)
    CONSTRAINT fk_user_watchlist FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;