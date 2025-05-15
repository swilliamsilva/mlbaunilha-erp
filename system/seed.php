# Reexecutar após reset para gerar seed.php novamente

seed_php = """
<?php
$host = 'mysql.railway.internal';
$user = 'root';
$password = 'cFaIcVJkdQmOVYwXTJYrODLUMweJIgNa';
$database = 'railway';
$port = 3306;

$mysqli = new mysqli($host, $user, $password, $database, $port);
if ($mysqli->connect_error) {
    die(" Falha na conexão: " . $mysqli->connect_error);
}

function inserir($sql, $mysqli) {
    if ($mysqli->query($sql) === TRUE) {
        echo " Inserção realizada.<br>";
    } else {
        echo " Erro: " . $mysqli->error . "<br>";
    }
}

// CLIENTES
$clientes = [
    ["João Silva", "123.456.789-01", "01001-000", "11988887777", "joao@gmail.com"],
    ["Maria Souza", "987.654.321-00", "04567-890", "11977776666", "maria@gmail.com"],
    ["Carlos Alberto", "321.654.987-00", "08230-030", "11966665555", "carlos@gmail.com"],
    ["Ana Paula", "111.222.333-44", "06700-000", "11955554444", "ana@gmail.com"],
    ["Lucas Lima", "555.666.777-88", "15015-000", "11944443333", "lucas@gmail.com"],
    ["Fernanda Dias", "999.888.777-66", "50030-030", "11933332222", "fernanda@gmail.com"],
    ["Paulo Cesar", "101.202.303-40", "40100-000", "11922221111", "paulo@gmail.com"],
    ["Camila Ramos", "333.444.555-66", "70345-610", "11911110000", "camila@gmail.com"],
    ["Ricardo Alves", "777.888.999-00", "30130-010", "11900009999", "ricardo@gmail.com"],
    ["Juliana Moraes", "888.777.666-55", "64000-000", "11888887777", "juliana@gmail.com"]
];

foreach ($clientes as $c) {
    $sql = "INSERT INTO clientes (nome, cpf, cep, telefone, email) VALUES ('$c[0]', '$c[1]', '$c[2]', '$c[3]', '$c[4]')";
    inserir($sql, $mysqli);
}

// PRODUTOS
$produtos = [
    ["Fone de Ouvido Bluetooth JBL", "https://www.mercadolivre.com.br/fone-jbl", 80, 5, 150, 20, 15],
    ["Echo Dot Alexa 4ª geração", "https://www.mercadolivre.com.br/echo-dot", 200, 0, 300, 0, 5],
    ["Câmera Wi-Fi Intelbras", "https://www.mercadolivre.com.br/camera-intelbras", 120, 10, 200, 15, 8],
    ["Smartwatch Xiaomi Mi Band 6", "https://www.mercadolivre.com.br/mi-band", 180, 0, 250, 10, 10],
    ["Ventilador Arno Turbo Silencio", "https://www.mercadolivre.com.br/ventilador-arno", 130, 15, 220, 25, 6],
    ["Liquidificador Oster", "https://www.mercadolivre.com.br/liquidificador", 90, 0, 140, 10, 7],
    ["Caixa de Som Bluetooth", "https://www.mercadolivre.com.br/caixa-bluetooth", 75, 5, 120, 15, 9],
    ["HD Externo 1TB Seagate", "https://www.mercadolivre.com.br/hd-externo", 250, 25, 350, 0, 4],
    ["Notebook Lenovo Ideapad 3", "https://www.mercadolivre.com.br/notebook-lenovo", 2200, 150, 3000, 0, 3],
    ["Smartphone Samsung Galaxy A32", "https://www.mercadolivre.com.br/galaxy-a32", 1100, 50, 1500, 20, 5]
];

foreach ($produtos as $p) {
    $sql = "INSERT INTO produtos (nome, codigo_produto, link_ml, preco_compra, cupom_desconto, preco_venda, valor_frete, saldo_estoque)
            VALUES ('$p[0]', UUID(), '$p[1]', $p[2], $p[3], $p[4], $p[5], $p[6])";
    inserir($sql, $mysqli);
}

// ESTOQUE (1 variação para cada produto)
for ($i = 1; $i <= 10; $i++) {
    $sql = "INSERT INTO estoque (produto_id, variacao, saldo) VALUES ($i, 'Padrão', 5)";
    inserir($sql, $mysqli);
}

// PEDIDOS (simplesmente ligando cliente e produto)
for ($i = 1; $i <= 10; $i++) {
    $cid = rand(1, 10);
    $pid = rand(1, 10);
    $qtd = rand(1, 3);
    $sql = "INSERT INTO pedidos (data_pedido, data_entrega_prevista, cliente_id, produto_id, quantidade_solicitada, quantidade_entregue, preco, frete, status)
            VALUES (NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), $cid, $pid, $qtd, 0, 100.00, 15.00, 'pendente')";
    inserir($sql, $mysqli);
}

// CUPONS (ligar aleatoriamente)
for ($i = 1; $i <= 10; $i++) {
    $pid = rand(1, 10);
    $cid = rand(1, 10);
    $sql = "INSERT INTO cupons (produto_id, pedido_id, valor_cupom, data_validade, condicao)
            VALUES ($pid, $cid, 10.00, DATE_ADD(NOW(), INTERVAL 30 DAY), 'Cupom válido acima de R$100')";
    inserir($sql, $mysqli);
}

echo "<br> Dados de exemplo inseridos com sucesso!";
?>
"""

with open("/mnt/data/seed.php", "w") as f:
    f.write(seed_php)

"/mnt/data/seed.php"
