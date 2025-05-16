<?php
// application/controllers/Produtos.php
class Produtos extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Produto_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    public function index() {
        $data['produtos'] = $this->Produto_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('produtos/index', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $this->load->view('templates/header');
        $this->load->view('produtos/create');
        $this->load->view('templates/footer');
    }

    public function store() {
        $data = array(
            'codigo_produto' => $this->input->post('codigo_produto'),
            'nome' => $this->input->post('nome'),
            'descricao' => $this->input->post('descricao'),
            'link_ml' => $this->input->post('link_ml'),
            'preco_compra' => $this->input->post('preco_compra'),
            'cupom_desconto' => $this->input->post('cupom_desconto'),
            'preco_venda' => $this->input->post('preco_venda'),
            'valor_frete' => $this->input->post('valor_frete'),
            'saldo_estoque' => $this->input->post('saldo_estoque')
        );

        $this->Produto_model->insert($data);
        redirect('produtos');
    }
}
