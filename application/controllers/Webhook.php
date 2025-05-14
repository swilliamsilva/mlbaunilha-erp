// application/controllers/Webhook.php
class Webhook extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Pedido_model');
    }

    public function atualizar() {
        $input = json_decode(file_get_contents('php://input'), true);
        $pedido_id = $input['pedido_id'] ?? null;
        $status = $input['status'] ?? null;

        if ($pedido_id && $status) {
            if ($status === 'cancelado') {
                $this->Pedido_model->delete($pedido_id);
                http_response_code(200);
                echo json_encode(['message' => 'Pedido cancelado e removido']);
            } else {
                $this->Pedido_model->update_status($pedido_id, $status);
                http_response_code(200);
                echo json_encode(['message' => 'Status atualizado']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
        }
    }
}
