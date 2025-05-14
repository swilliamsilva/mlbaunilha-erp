// application/controllers/Email.php
class Email extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->model('Pedido_model');
        $this->load->model('Cliente_model');
    }

    public function enviar($pedido_id) {
        $pedido = $this->Pedido_model->get_by_id($pedido_id);
        $cliente = $this->Cliente_model->get_by_id($pedido->cliente_id);

        $this->email->from('loja@mlbaunilha.com', 'MLBaunilha');
        $this->email->to($cliente->email);
        $this->email->subject('Confirmação de Pedido #'.$pedido->id);
        $this->email->message("Olá {$cliente->nome},\nSeu pedido foi confirmado com entrega prevista para {$pedido->data_entrega_prevista}.");

        if ($this->email->send()) {
            echo 'E-mail enviado com sucesso';
        } else {
            echo 'Erro ao enviar e-mail';
        }
    }
}
