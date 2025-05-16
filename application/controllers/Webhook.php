<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Pedido_model $pedido
 */
class Webhook extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pedido_model', 'pedido', TRUE); // Carrega com alias
    }

    public function atualizar() {
        try {
            header('Content-Type: application/json');
            
            // Verifica método HTTP
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método não permitido', 405);
            }

            $input = json_decode(file_get_contents('php://input'), true);
            
            // Validação básica
            if (empty($input['pedido_id']) || empty($input['status'])) {
                throw new Exception('Dados inválidos', 400);
            }

            $this->processar_webhook($input['pedido_id'], $input['status']);
            
            echo json_encode(['success' => true]);
            
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }

    private function processar_webhook($pedido_id, $status) {
        $pedido = $this->pedido->get_by_id($pedido_id);
        
        if (!$pedido) {
            throw new Exception('Pedido não encontrado', 404);
        }

        switch (strtolower($status)) {
            case 'cancelado':
                $this->pedido->cancelar($pedido_id);
                break;
                
            default:
                $this->pedido->atualizar_status($pedido_id, $status);
        }
    }
}
