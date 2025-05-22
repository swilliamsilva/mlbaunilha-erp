<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Pedido_model $pedido
 * @property CI_Input $input
 * @property CI_Output $output
 */
class Webhook extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pedido_model', 'pedido');
    }

    public function atualizar() {
        try {
            // Verifica método HTTP correto
            if ($this->input->server('REQUEST_METHOD') !== 'POST') {
                throw new RuntimeException("Método não permitido", 405);
            }

            // Processar payload
            $payload = $this->obter_payload_valido();
            $this->processar_webhook($payload['pedido_id'], $payload['status']);

            $this->enviar_resposta_sucesso();

        } catch (Exception $e) {
            $this->enviar_resposta_erro($e);
        }
    }

    private function obter_payload_valido() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("JSON inválido", 400);
        }

        $this->validar_campos_obrigatorios($input);
        
        return [
            'pedido_id' => (int)$input['pedido_id'],
            'status' => strtolower($input['status'])
        ];
    }

    private function validar_campos_obrigatorios($input) {
        $campos_obrigatorios = ['pedido_id', 'status'];
        
        foreach ($campos_obrigatorios as $campo) {
            if (empty($input[$campo])) {
                throw new InvalidArgumentException("Campo obrigatório faltando: {$campo}", 400);
            }
        }
    }

    private function processar_webhook($pedido_id, $status) {
        $pedido = $this->pedido->obter_por_id($pedido_id);
        
        if (!$pedido) {
            throw new RuntimeException("Pedido não encontrado: {$pedido_id}", 404);
        }

        $this->aplicar_transicao_status($pedido_id, $status);
    }

    private function aplicar_transicao_status($pedido_id, $status) {
        switch ($status) {
            case 'cancelado':
                $metodo = 'cancelar';
                break;
            case 'processando':
                $metodo = 'marcar_como_processando';
                break;
            case 'enviado':
                $metodo = 'marcar_como_enviado';
                break;
            default:
                $metodo = 'atualizar_status';
        }

        if (!method_exists($this->pedido, $metodo)) {
            throw new RuntimeException("Ação não implementada para status: {$status}", 501);
        }

        $this->pedido->{$metodo}($pedido_id);
    }

    private function enviar_resposta_sucesso() {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true]));
    }

    private function enviar_resposta_erro(Exception $e) {
        log_message('error', 'Webhook: ' . $e->getMessage());
        
        $this->output
            ->set_status_header($e->getCode() ?: 500)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]));
    }
}
