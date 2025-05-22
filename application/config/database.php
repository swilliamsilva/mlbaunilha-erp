<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Carrega variáveis de ambiente
require_once(APPPATH . 'config/env.php');

// Verificação de variáveis essenciais
$required_env_vars = ['MYSQLHOST', 'MYSQLUSER', 'MYSQLPASSWORD', 'MYSQLDATABASE'];
foreach ($required_env_vars as $var) {
    if (!getenv($var)) {
        log_message('error', "Variável de ambiente essencial faltando: $var");
        header('HTTP/1.1 503 Service Unavailable');
        exit("Erro de configuração do sistema. Contate o suporte.");
    }
}

$db['default'] = [
    'dsn'       => '',
    'hostname'  => getenv('MYSQLHOST'),
    'username'  => getenv('MYSQLUSER'),
    'password'  => getenv('MYSQLPASSWORD'),
    'database'  => getenv('MYSQLDATABASE'),
    'port'      => getenv('MYSQLPORT') ?: 3306,
    'dbdriver'  => 'mysqli',
    'dbprefix'  => '',
    'pconnect'  => false,
    
    // Debug apenas em desenvolvimento
    'db_debug'  => (ENVIRONMENT !== 'production') ? true : [
        'display_error' => false,
        'log_threshold' => 1
    ],
    
    // Configurações de cache
    'cache_on'  => (ENVIRONMENT === 'production'),
    'cachedir'  => APPPATH . 'database/cache/',
    
    // Configurações de charset
    'char_set'  => 'utf8mb4',
    'dbcollat'  => 'utf8mb4_unicode_ci',
    
    // SSL para conexões seguras (Recomendado para Railway/Cloud)
    'encrypt'   => [
        'ssl_key'    => null,
        'ssl_cert'   => null,
        'ssl_ca'     => null,
        'ssl_capath' => null,
        'ssl_cipher' => 'AES256-SHA',
        'ssl_verify' => true
    ],
    
    // Configurações de failover
    'failover' => [
        [
            'hostname' => getenv('MYSQL_HOST_FAILOVER') ?: 'localhost',
            'username' => getenv('MYSQL_USER_FAILOVER') ?: 'backup_user',
            'password' => getenv('MYSQL_PASS_FAILOVER') ?: 'backup_pass',
            'database' => getenv('MYSQL_DB_FAILOVER') ?: 'backup_db',
            'dbdriver' => 'mysqli',
            'port'     => getenv('MYSQL_PORT_FAILOVER') ?: 3306
        ]
    ],
    
    // Otimizações para produção
    'save_queries' => (ENVIRONMENT !== 'production'),
    'stricton'     => (ENVIRONMENT === 'production'),
    'compress'     => (ENVIRONMENT === 'production'),
    
    // Timeouts personalizados
    'connect_timeout' => 10,
    'options' => [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
];

// Conexão de teste imediata
try {
    $test_conn = @new mysqli(
        $db['default']['hostname'],
        $db['default']['username'],
        $db['default']['password'],
        $db['default']['database'],
        $db['default']['port']
    );
    
    if ($test_conn->connect_error) {
        throw new Exception("Connection failed: " . $test_conn->connect_error);
    }
    $test_conn->close();
    
} catch (Exception $e) {
    log_message('error', 'Database connection test failed: ' . $e->getMessage());
    header('HTTP/1.1 503 Service Unavailable');
    exit("Erro na conexão com o banco de dados. Tente novamente mais tarde.");
}
