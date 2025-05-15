<?php
// Railway conexão interna (exclusivo para backend rodando também no Railway)
$host = 'mysql.railway.internal';
$user = 'root';
$password = 'cFaIcVJkdQmOVYwXTJYrODLUMweJIgNa';
$database = 'railway';
$port = 3306;

// Conectar ao banco
$mysqli = new mysqli($host, $user, $password, $database, $port);
if ($mysqli->connect_error) {
    die(" Falha na conexão: " . $mysqli->connect_error);
}

// SQL para criar todas as tabelas
$sql = <<<SQL
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    cep VARCHAR(9) NOT NULL,
    telefone VARCHAR(15),
    email VARCHAR(100),
    observacao TEXT,
    rua VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_produto VARCHAR(50) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    link_ml TEXT,
    preco_compra DECIMAL(10,2) NOT NULL,
    cupom_desconto DECIMAL(10,2),
    preco_venda DECIMAL(10,2) NOT NULL,
    valor_frete DECIMAL(10,2) NOT NULL,
    saldo_estoque INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    variacao VARCHAR(100),
    saldo INT,
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_pedido DATE NOT NULL,
    data_entrega_prevista DATE,
    data_entrega_efetuada DATE,
    cliente_id INT,
    produto_id INT,
    quantidade_solicitada INT,
    quantidade_entregue INT,
    preco DECIMAL(10,2),
    frete DECIMAL(10,2),
    status ENUM('pendente', 'enviado', 'entregue', 'cancelado') DEFAULT 'pendente',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    pedido_id INT,
    valor_cupom DECIMAL(10,2),
    data_validade DATE,
    condicao VARCHAR(100),
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

// Executa o SQL
if ($mysqli->multi_query($sql)) {
    echo " Tabelas criadas ou já existentes.";
} else {
    echo " Erro ao criar tabelas: " . $mysqli->error;
}
?>
