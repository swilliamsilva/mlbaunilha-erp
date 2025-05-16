<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * @property CI_Email $email
 * @property Pedido_model $pedido
 * @property Cliente_model $cliente
 * @property CI_Email $email
 */

class Email extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Carregar dependências
        $this->load->model('Pedido_model', 'pedido', TRUE);
        $this->load->model('Cliente_model', 'cliente', TRUE);
        
        // Configurar biblioteca de email
        $this->load->library('email');
        $this->configurar_email();
    }

    public function enviar($pedido_id) {
        try {
            $pedido = $this->pedido->obter_por_id($pedido_id);
            $cliente = $this->cliente->obter_por_id($pedido->cliente_id);

            $this->enviar_email_confirmacao($cliente, $pedido);
            
            echo 'E-mail enviado com sucesso para ' . $cliente->email;
            
        } catch (Exception $e) {
            log_message('error', 'Erro ao enviar email: ' . $e->getMessage());
            echo 'Erro: ' . $e->getMessage();
        }
    }

    private function configurar_email() {
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.seudominio.com',
            'smtp_port' => 587,
            'smtp_user' => 'contato@seudominio.com',
            'smtp_pass' => 'sua_senha',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];
        $this->email->initialize($config);
    }

    private function enviar_email_confirmacao($cliente, $pedido) {
        $this->email->from('loja@mlbaunilha.com', 'MLBaunilha');
        $this->email->to($cliente->email);
        $this->email->subject("Pedido #{$pedido->id} - Confirmação");
        
        $mensagem = $this->load->view('emails/confirmacao_pedido', [
            'cliente' => $cliente,
            'pedido' => $pedido
        ], TRUE);

        $this->email->message($mensagem);

        if (!$this->email->send()) {
            throw new Exception('Falha no envio: ' . $this->email->print_debugger());
        }
    }
}
