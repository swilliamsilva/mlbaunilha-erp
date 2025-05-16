-- Limpeza de tabelas dependentes (MSSQL)
DELETE FROM cupons;
DELETE FROM pedidos;
DELETE FROM estoque;
DELETE FROM produtos;
DELETE FROM clientes;

-- Resetar identity das tabelas (opcional, se necessário)
DBCC CHECKIDENT ('cupons', RESEED, 0);
DBCC CHECKIDENT ('pedidos', RESEED, 0);
DBCC CHECKIDENT ('estoque', RESEED, 0);
DBCC CHECKIDENT ('produtos', RESEED, 0);
DBCC CHECKIDENT ('clientes', RESEED, 0);

-- Adicionar colunas de timestamp (MSSQL)
ALTER TABLE pedidos ADD 
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    deleted_at DATETIME NULL;

-- Verificação de conexão (sintaxe MSSQL)
SELECT 'Conexão ativa' AS status;

-- Inserção de cliente
INSERT INTO clientes (cpf, nome, cep, telefone, email, observacao)
VALUES ('12345678900', 'Cliente Teste', '01001000', '(11) 99999-9999', 'cliente@teste.com', 'Teste automatizado');

-- Inserção de produto
INSERT INTO produtos (codigo_produto, nome, descricao, link_ml, preco_compra, cupom_desconto, preco_venda, valor_frete, saldo_estoque)
VALUES ('PROD123', 'Produto Teste', 'Descrição Teste', 'https://produto.ml/teste', 50.00, 5.00, 100.00, 20.00, 10);

-- Inserção de estoque usando SCOPE_IDENTITY()
INSERT INTO estoque (produto_id, variacao, saldo)
VALUES (SCOPE_IDENTITY(), 'Tamanho M', 5);

-- Inserção de pedidos
INSERT INTO pedidos (
    data_pedido,
    data_entrega_prevista,
    cliente_id,
    produto_id,
    quantidade_solicitada,
    quantidade_entregue,
    preco,
    frete,
    status
) VALUES 
(GETDATE(), DATEADD(DAY, 5, GETDATE()), 1, IDENT_CURRENT('produtos'), 2, 0, 100.00, 15.00, 'pendente'),
(GETDATE(), DATEADD(DAY, 6, GETDATE()), 1, IDENT_CURRENT('produtos'), 1, 0, 100.00, 15.00, 'pendente'),
(GETDATE(), DATEADD(DAY, 7, GETDATE()), 1, IDENT_CURRENT('produtos'), 3, 0, 100.00, 20.00, 'pendente');

-- Inserção de cupom
INSERT INTO cupons (produto_id, pedido_id, valor_cupom, data_validade, condicao)
VALUES (
    IDENT_CURRENT('produtos'), 
    IDENT_CURRENT('pedidos'), 
    10.00, 
    DATEADD(DAY, 10, GETDATE()), 
    'Válido para compras acima de R$100'
);

-- Teste de integridade
SELECT TOP 100
    p.id AS pedido_id,
    c.nome AS cliente,
    pr.nome AS produto
FROM pedidos p
JOIN clientes c ON c.id = p.cliente_id
JOIN produtos pr ON pr.id = p.produto_id;

-- Transação de teste
BEGIN TRANSACTION;
    UPDATE pedidos SET status = 'cancelado' WHERE id = 2;
    DELETE FROM pedidos WHERE id = 3;
ROLLBACK; -- Troque para COMMIT para confirmar

-- Verificação final
SELECT TOP 100 * FROM pedidos ORDER BY id DESC;
