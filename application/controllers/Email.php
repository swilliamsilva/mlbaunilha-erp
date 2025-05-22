<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Pedido_model $pedido
 * @property Cliente_model $cliente
 * @property CI_Email $email
 * @property CI_Config $config
 */
class Email_controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('Pedido_model', 'pedido');
        $this->load->model('Cliente_model', 'cliente');
        $this->load->library('email');
        $this->load->config('email');
        
        $this->configurar_email();
    }

    public function enviar($pedido_id) {
        try {
            $this->validar_pedido($pedido_id);
            $pedido = $this->pedido->obter_por_id($pedido_id);
            $cliente = $this->cliente->obter_por_id($pedido->cliente_id);

            $this->enviar_email_confirmacao($cliente, $pedido);
            
            $this->json_response([
                'success' => true,
                'message' => 'E-mail enviado para ' . obfuscate_email($cliente->email)
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Erro ao enviar email: ' . $e->getMessage());
            $this->json_response([
                'success' => false,
                'error' => 'Falha no envio do e-mail'
            ], 500);
        }
    }

    private function configurar_email() {
        $this->email->initialize([
            'protocol'  => $this->config->item('protocol'),
            'smtp_host' => $this->config->item('smtp_host'),
            'smtp_port' => $this->config->item('smtp_port'),
            'smtp_user' => $this->config->item('smtp_user'),
            'smtp_pass' => $this->config->item('smtp_pass'),
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
            'smtp_crypto' => 'tls'
        ]);
    }

    private function validar_pedido($pedido_id) {
        if (!ctype_digit((string)$pedido_id)) {
            throw new InvalidArgumentException('ID do pedido inválido');
        }
    }

    private function enviar_email_confirmacao($cliente, $pedido) {
        $this->email->clear();
        $this->email->from(
            $this->config->item('from_email'), 
            $this->config->item('from_name')
        );
        $this->email->to($cliente->email);
        $this->email->bcc($this->config->item('bcc_email'));
        $this->email->subject("Pedido #{$pedido->id} - Confirmação | " . date('d/m/Y'));

        $dados = [
            'cliente' => $cliente,
            'pedido' => $pedido,
            'data' => date('d/m/Y H:i')
        ];

        $mensagem = $this->load->view('emails/confirmacao_pedido', $dados, TRUE);
        $this->email->message($mensagem);

        if (!$this->email->send()) {
            throw new RuntimeException($this->email->print_debugger(['headers', 'subject', 'body']));
        }
    }
}
