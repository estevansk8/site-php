drop database gerenciaCampeonatos;
CREATE DATABASE gerenciaCampeonatos;
USE gerenciaCampeonatos;

-- Tabela Usuario
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL, -- Senha criptografada
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela Clube
CREATE TABLE Clube (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cidade VARCHAR(100),
    estadio VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data de criação
    created_by VARCHAR(100) -- Pode ser NULL se não informado
);

-- Tabela Jogador
CREATE TABLE Jogador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    posicao VARCHAR(50),
    time_id INT, 
    FOREIGN KEY (time_id) REFERENCES Clube(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data de criação
    created_by VARCHAR(100) -- Pode ser NULL se não informado
);

-- Tabela Campeonato
CREATE TABLE Campeonato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    ano INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data de criação
    created_by VARCHAR(100) -- Pode ser NULL se não informado
);

-- Tabela Partida
CREATE TABLE Partida (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data DATE,
    local VARCHAR(100),
    campeonato_id INT,  -- Relacionamento com Campeonato
    time_home_id INT,   -- ID do time da casa
    time_away_id INT,   -- ID do time visitante
    FOREIGN KEY (campeonato_id) REFERENCES Campeonato(id),  -- Chave estrangeira
    FOREIGN KEY (time_home_id) REFERENCES Clube(id),        -- Chave estrangeira para time da casa
    FOREIGN KEY (time_away_id) REFERENCES Clube(id),        -- Chave estrangeira para time visitante
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data de criação
    created_by VARCHAR(100) -- Pode ser NULL se não informado
);

-- Tabela NxN Time x Campeonato
CREATE TABLE TimeCampeonato (
    time_id INT,
    campeonato_id INT,
    PRIMARY KEY (time_id, campeonato_id),
    FOREIGN KEY (time_id) REFERENCES Clube(id),
    FOREIGN KEY (campeonato_id) REFERENCES Campeonato(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data de criação
    created_by VARCHAR(100) -- Pode ser NULL se não informado
);


ALTER TABLE Partida
ADD COLUMN time_home_gols INT NOT NULL,
ADD COLUMN time_away_gols INT NOT NULL,
ADD COLUMN vencedor INT GENERATED ALWAYS AS (
    CASE
        WHEN time_home_gols > time_away_gols THEN time_home_id
        WHEN time_home_gols < time_away_gols THEN time_away_id
        ELSE NULL
    END
) STORED;

ALTER TABLE Campeonato MODIFY ano YEAR;


-- Inserção de dados nas tabelas

-- Inserindo Usuário
INSERT INTO User (nome, email, senha, created_at, updated_at) 
VALUES ('Carlos Silva', 'carlos.silva@email.com', 'senha123', '2024-11-19 10:00:00', '2024-11-19 10:00:00');

-- Inserindo clubes
INSERT INTO Clube (nome, cidade, estadio, created_by) VALUES
('Flamengo', 'Rio de Janeiro', 'Maracanã', 'admin'),
('Palmeiras', 'São Paulo', 'Allianz Parque', 'admin'),
('Corinthians', 'São Paulo', 'Arena Corinthians', 'admin'),
('Grêmio', 'Porto Alegre', 'Arena do Grêmio', 'admin'),
('Internacional', 'Porto Alegre', 'Beira-Rio', 'admin');

-- Inserindo jogadores
INSERT INTO Jogador (nome, posicao, time_id, created_by) VALUES
('Gabriel Barbosa', 'Atacante', 1, 'admin'),
('Dudu', 'Atacante', 2, 'admin'),
('Fagner', 'Lateral', 3, 'admin'),
('Douglas Costa', 'Atacante', 4, 'admin'),
('Andrés D’Alessandro', 'Meia', 5, 'admin');

-- Inserindo campeonatos
INSERT INTO Campeonato (nome, ano, created_by) VALUES
('Campeonato Brasileiro', 2023, 'admin'),
('Copa do Brasil', 2023, 'admin'),
('Libertadores', 2023, 'admin'),
('Campeonato Gaúcho', 2023, 'admin'),
('Campeonato Paulista', 2023, 'admin');

-- Inserindo TimeCampeonato com base nas partidas
-- Campeonato Brasileiro (ID 1)
INSERT INTO TimeCampeonato (time_id, campeonato_id, created_by) VALUES
(1, 1, 'admin'), -- Flamengo
(2, 1, 'admin'), -- Palmeiras
(3, 1, 'admin'), -- Corinthians
(4, 1, 'admin'); -- Grêmio

-- Campeonato Gaúcho (ID 4)
INSERT INTO TimeCampeonato (time_id, campeonato_id, created_by) VALUES
(4, 4, 'admin'), -- Grêmio
(5, 4, 'admin'); -- Internacional

-- Campeonato Paulista (ID 5)
INSERT INTO TimeCampeonato (time_id, campeonato_id, created_by) VALUES
(2, 5, 'admin'), -- Palmeiras
(3, 5, 'admin'); -- Corinthians

-- Inserindo partidas
INSERT INTO Partida (data, local, campeonato_id, time_home_id, time_away_id, time_home_gols, time_away_gols, created_by) VALUES
('2023-10-01', 'Maracanã', 1, 1, 2, 2, 1, 'admin'),
('2023-10-05', 'Arena Corinthians', 1, 3, 4, 1, 1, 'admin'),
('2023-10-10', 'Beira-Rio', 4, 5, 4, 0, 2, 'admin'),
('2023-10-15', 'Allianz Parque', 5, 2, 3, 3, 1, 'admin'),
('2023-10-20', 'Arena do Grêmio', 4, 4, 5, 2, 0, 'admin');



