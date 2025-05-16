<?php
// application/controllers/Estoque.php
class Estoque extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Estoque_model');
        $this->load->model('Produto_model');
    }

    public function index() {
        $data['estoques'] = $this->Estoque_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('estoque/index', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $data['produtos'] = $this->Produto_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('estoque/create', $data);
        $this->load->view('templates/footer');
    }

    public function store() {
        $data = array(
            'produto_id' => $this->input->post('produto_id'),
            'variacao' => $this->input->post('variacao'),
            'saldo' => $this->input->post('saldo')
        );
        $this->Estoque_model->insert($data);
        redirect('estoque');
    }
}
