-- ============================================================
-- LINEBOT / LINA-01 — Banco de Dados da Feira Tecnológica
-- MySQL 8.0+ | Engine InnoDB | Charset utf8mb4
-- ============================================================

CREATE DATABASE IF NOT EXISTS feira_iot
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE feira_iot;

-- ============================================================
-- ENTIDADE 1: VISITORS
-- Guarda os visitantes que participam da experiência na feira.
-- O e-mail é único para impedir cadastros duplicados.
-- ============================================================
CREATE TABLE IF NOT EXISTS visitors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do visitante',
    name VARCHAR(120) NOT NULL COMMENT 'Nome completo do visitante',
    email VARCHAR(150) NOT NULL UNIQUE COMMENT 'E-mail único para evitar duplicidade',
    interest VARCHAR(100) NOT NULL COMMENT 'Área de interesse informada pelo visitante',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do cadastro'
) ENGINE=InnoDB;

-- ============================================================
-- ENTIDADE 2: TEST_BATTERIES
-- Registra uma tentativa/teste do robô seguidor de linha.
-- visitor_id é opcional. Se um visitante for excluído, o teste
-- permanece salvo e sua FK passa a NULL (ON DELETE SET NULL).
-- ============================================================
CREATE TABLE IF NOT EXISTS test_batteries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do teste',
    visitor_id INT UNSIGNED NULL COMMENT 'Visitante associado ao teste, quando informado',
    avg_speed DECIMAL(5,2) NOT NULL COMMENT 'Velocidade média registrada em metros por segundo',
    lap_time DECIMAL(6,2) NOT NULL COMMENT 'Tempo total da volta em segundos',
    status ENUM('Concluído', 'Recorde da Feira', 'Interrompido') NOT NULL DEFAULT 'Concluído' COMMENT 'Situação final do teste',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do teste',
    CONSTRAINT fk_test_batteries_visitor
        FOREIGN KEY (visitor_id) REFERENCES visitors(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    INDEX idx_test_batteries_created_at (created_at),
    INDEX idx_test_batteries_visitor_id (visitor_id)
) ENGINE=InnoDB;

-- Não são inseridos visitantes fictícios automaticamente.
-- Os registros serão criados pela interface pública e pela equipe.
