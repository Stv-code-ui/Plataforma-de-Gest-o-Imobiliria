-- schema.sql
-- Base de dados para o MVP da Plataforma de Gestão Imobiliária

CREATE DATABASE IF NOT EXISTS gestao_imobiliaria
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE gestao_imobiliaria;

-- ========================
-- Utilizadores
-- ========================
CREATE TABLE utilizadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    tipo ENUM('proprietario', 'interessado', 'admin') NOT NULL DEFAULT 'interessado',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================
-- Imóveis
-- ========================
CREATE TABLE imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proprietario_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT,
    tipo ENUM('casa', 'apartamento', 'quarto', 'anexo') NOT NULL,
    tipologia VARCHAR(10),          -- T0, T1, T2, T3...
    preco DECIMAL(12,2) NOT NULL,
    localizacao VARCHAR(150) NOT NULL,
    quartos INT DEFAULT 0,
    verificado BOOLEAN NOT NULL DEFAULT FALSE,
    disponivel BOOLEAN NOT NULL DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proprietario_id) REFERENCES utilizadores(id) ON DELETE CASCADE
);

-- ========================
-- Fotos dos imóveis
-- ========================
CREATE TABLE imovel_fotos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    url VARCHAR(255) NOT NULL,
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE
);

-- ========================
-- Mensagens (contacto directo / chat integrado)
-- ========================
CREATE TABLE mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remetente_id INT NOT NULL,
    destinatario_id INT NOT NULL,
    imovel_id INT,
    conteudo TEXT NOT NULL,
    lida BOOLEAN NOT NULL DEFAULT FALSE,
    enviada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (remetente_id) REFERENCES utilizadores(id),
    FOREIGN KEY (destinatario_id) REFERENCES utilizadores(id),
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE SET NULL
);

-- ========================
-- Visitas agendadas
-- ========================
CREATE TABLE visitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    interessado_id INT NOT NULL,
    data_visita DATETIME NOT NULL,
    estado ENUM('pendente', 'confirmada', 'cancelada', 'concluida') NOT NULL DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE,
    FOREIGN KEY (interessado_id) REFERENCES utilizadores(id) ON DELETE CASCADE
);

-- ========================
-- Favoritos
-- ========================
CREATE TABLE favoritos (
    utilizador_id INT NOT NULL,
    imovel_id INT NOT NULL,
    PRIMARY KEY (utilizador_id, imovel_id),
    FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE,
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE
);