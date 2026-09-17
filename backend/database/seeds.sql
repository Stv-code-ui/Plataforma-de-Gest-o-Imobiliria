-- Dados de demonstracao para desenvolvimento local.
-- Palavra-passe das contas: password
USE gestao_imobiliaria;

INSERT IGNORE INTO utilizadores (id, nome, email, senha, telefone, tipo) VALUES
    (1, 'Administrador', 'admin@gestao.local', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', '+244900000001', 'admin'),
    (2, 'Dona Maria', 'maria@gestao.local', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', '+244900000002', 'proprietario'),
    (3, 'Joao Silva', 'joao@gestao.local', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', '+244900000003', 'interessado');

INSERT IGNORE INTO imoveis (id, proprietario_id, titulo, descricao, tipo, tipologia, preco, localizacao, quartos, verificado, disponivel) VALUES
    (1, 2, 'Apartamento mobilado em Talatona', 'Apartamento luminoso com varanda e estacionamento.', 'apartamento', 'T2', 350000.00, 'Talatona, Luanda', 2, TRUE, TRUE),
    (2, 2, 'Casa familiar no Benfica', 'Casa com quintal, cozinha equipada e boa acessibilidade.', 'casa', 'T3', 500000.00, 'Benfica, Luanda', 3, TRUE, TRUE);

INSERT IGNORE INTO imovel_fotos (id, imovel_id, url) VALUES
    (1, 1, '/storage/imoveis/apartamento-talatona.jpg'),
    (2, 2, '/storage/imoveis/casa-benfica.jpg');

INSERT IGNORE INTO mensagens (id, remetente_id, destinatario_id, imovel_id, conteudo, lida) VALUES
    (1, 3, 2, 1, 'Boa tarde. O apartamento ainda esta disponivel?', FALSE),
    (2, 2, 3, 1, 'Boa tarde. Sim, podemos agendar uma visita.', TRUE);

INSERT IGNORE INTO visitas (id, imovel_id, interessado_id, data_visita, estado) VALUES
    (1, 1, 3, '2026-10-05 10:00:00', 'confirmada'),
    (2, 2, 3, '2026-10-07 14:30:00', 'pendente');

INSERT IGNORE INTO favoritos (utilizador_id, imovel_id) VALUES
    (3, 1),
    (3, 2);