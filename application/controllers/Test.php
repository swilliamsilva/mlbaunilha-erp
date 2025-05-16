<?php
class Test extends CI_Controller {
    public function index() {
        echo "Funcionou!";
        error_log("Teste de log via CodeIgniter"); // Mensagem de teste no log
    }
}