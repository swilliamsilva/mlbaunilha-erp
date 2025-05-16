<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Teste para ver se está rodando
echo "INDEX ATIVADO<br>";
echo "HOST: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "INDEX ATIVADO COM SUCESSO<br>";
var_dump($_SERVER['REQUEST_URI']);

phpinfo();  // opcional para ver tudo
