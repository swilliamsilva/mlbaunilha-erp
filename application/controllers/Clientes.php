<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Cliente_model $Cliente_model
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 */
class Clientes extends MY_Controller {

    // ... (mantenha o restante do código igual até o método validar_cpf)

    public function validar_cpf($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $this->form_validation->set_message('validar_cpf', 'O {field} informado é inválido');
            return false;
        }

        // Cálculo para validação
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $this->form_validation->set_message('validar_cpf', 'O {field} informado é inválido');
                return false;
            }
        }
        return true;
    }
} // Fechamento correto da classe
