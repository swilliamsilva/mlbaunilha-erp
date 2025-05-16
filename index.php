<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Teste para ver se está rodando
echo "INDEX ATIVADO<br>";
echo "HOST: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "URI: " . $_SERVER['REQUEST_URI'] . "<br>";

phpinfo();  // opcional para ver tudo
