-- Limpa tabelas dependentes
DELETE FROM cupons;
DELETE FROM pedidos;
DELETE FROM estoque;
DELETE FROM produtos;
DELETE FROM clientes;

-- Verifica conexão com banco
SELECT 'Conexão ativa' AS status;

-- Inserção de cliente
INSERT INTO clientes (cpf, nome, cep, telefone, email, observacao)
VALUES ('123.456.789-00', 'Cliente Teste', '01001-000', '(11) 99999-9999', 'cliente@teste.com', 'Teste automatizado');

-- Inserção de produto
INSERT INTO produtos (codigo_produto, nome, descricao, link_ml, preco_compra, cupom_desconto, preco_venda, valor_frete, saldo_estoque)
VALUES ('PROD123', 'Produto Teste', 'Descrição Teste', 'https://produto.ml/teste', 50.00, 5.00, 100.00, 20.00, 10);

-- Inserção de estoque com variação (usa SCOPE_IDENTITY() para MSSQL)
INSERT INTO estoque (produto_id, variacao, saldo)
VALUES (SCOPE_IDENTITY(), 'Tamanho M', 5);  -- SCOPE_IDENTITY() substitui LAST_INSERT_ID()

-- Inserção de pedidos (usa GETDATE() e DATEADD para MSSQL)
INSERT INTO pedidos (data_pedido, data_entrega_prevista, cliente_id, produto_id, quantidade_solicitada, quantidade_entregue, preco, frete, status)
VALUES 
(GETDATE(), DATEADD(DAY, 5, GETDATE()), 1, 1, 2, 0, 100.00, 15.00, 'pendente'),
(GETDATE(), DATEADD(DAY, 6, GETDATE()), 1, 1, 1, 0, 100.00, 15.00, 'pendente'),
(GETDATE(), DATEADD(DAY, 7, GETDATE()), 1, 1, 3, 0, 100.00, 20.00, 'pendente');

-- Inserção de cupom para um pedido
INSERT INTO cupons (produto_id, pedido_id, valor_cupom, data_validade, condicao)
VALUES (1, 1, 10.00, DATEADD(DAY, 10, GETDATE()), 'Válido para compras acima de R$100');

-- Teste de integridade referencial
SELECT pedidos.id, clientes.nome, produtos.nome
FROM pedidos
JOIN clientes ON clientes.id = pedidos.cliente_id
JOIN produtos ON produtos.id = pedidos.produto_id;

-- Teste de transação (usa BEGIN TRANSACTION para MSSQL)
BEGIN TRANSACTION;
UPDATE pedidos SET status = 'cancelado' WHERE id = 2;
DELETE FROM pedidos WHERE id = 3;
ROLLBACK;  -- Ou COMMIT para confirmar

-- Teste de ponta a ponta (verifica resultado)
SELECT TOP 100 * FROM pedidos ORDER BY id;  -- TOP substitui LIMIT
