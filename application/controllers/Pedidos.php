<?php
// application/controllers/Pedidos.php
class Pedidos extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Pedido_model');
        $this->load->model('Cliente_model');
        $this->load->model('Produto_model');
        $this->load->model('Cupom_model');
    }

    public function index() {
        $data['pedidos'] = $this->Pedido_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('pedidos/index', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $data['clientes'] = $this->Cliente_model->get_all();
        $data['produtos'] = $this->Produto_model->get_all();
        $data['cupons'] = $this->Cupom_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('pedidos/create', $data);
        $this->load->view('templates/footer');
    }

    public function store() {
        $subtotal = $this->input->post('preco') * $this->input->post('quantidade_solicitada');
        if ($subtotal > 200) {
            $frete = 0.00;
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15.00;
        } else {
            $frete = 20.00;
        }

        $data = array(
            'data_pedido' => date('Y-m-d'),
            'data_entrega_prevista' => $this->input->post('data_entrega_prevista'),
            'data_entrega_efetuada' => NULL,
            'cliente_id' => $this->input->post('cliente_id'),
            'produto_id' => $this->input->post('produto_id'),
            'quantidade_solicitada' => $this->input->post('quantidade_solicitada'),
            'quantidade_entregue' => 0,
            'preco' => $this->input->post('preco'),
            'frete' => $frete,
            'status' => 'pendente'
        );

        $this->Pedido_model->insert($data);
        redirect('pedidos');
    }
}
