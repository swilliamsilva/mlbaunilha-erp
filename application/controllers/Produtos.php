<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Produto_model $produto
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property CI_Session $session
 */
class Produtos extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Produto_model', 'produto');
    }

    public function index() {
        try {
            $data = [
                'produtos' => $this->produto->listar_todos(),
                'flash' => $this->session->flashdata()
            ];
            $this->load_template('produtos/index', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Produtos/index: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Erro ao carregar produtos');
            redirect(site_url('produtos'));
        }
    }

    public function create() {
        try {
            $data = ['flash' => $this->session->flashdata()];
            $this->load_template('produtos/create', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Produtos/create: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Erro ao carregar formulário');
            redirect(site_url('produtos'));
        }
    }

    public function store() {
        try {
            $this->validar_formulario();
            $this->processar_cadastro();
            
            $this->session->set_flashdata('success', 'Produto cadastrado com sucesso!');
            redirect(site_url('produtos'));
            
        } catch (Exception $e) {
            log_message('error', 'Produtos/store: ' . $e->getMessage());
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(site_url('produtos/create'));
        }
    }

    private function validar_formulario() {
        $this->form_validation->set_rules('codigo_produto', 'Código', 'required|is_unique[produtos.codigo_produto]');
        $this->form_validation->set_rules('nome', 'Nome', 'required|max_length[100]');
        $this->form_validation->set_rules('preco_compra', 'Preço de Compra', 'required|decimal|greater_than[0]');
        $this->form_validation->set_rules('preco_venda', 'Preço de Venda', 'required|decimal|greater_than[0]');
        $this->form_validation->set_rules('saldo_estoque', 'Estoque', 'required|integer|greater_than_equal_to[0]');

        if (!$this->form_validation->run()) {
            throw new RuntimeException(validation_errors('<li>', '</li>'));
        }
    }

    private function processar_cadastro() {
        $dados = $this->montar_dados_produto();
        
        if (!$this->produto->cadastrar($dados)) {
            throw new RuntimeException('Falha ao registrar produto');
        }
    }

    private function montar_dados_produto() {
        return [
            'codigo_produto' => $this->input->post('codigo_produto', true),
            'nome' => $this->input->post('nome', true),
            'descricao' => $this->input->post('descricao', true),
            'link_ml' => $this->input->post('link_ml', true),
            'preco_compra' => (float)$this->input->post('preco_compra', true),
            'cupom_desconto' => $this->input->post('cupom_desconto', true),
            'preco_venda' => (float)$this->input->post('preco_venda', true),
            'valor_frete' => (float)$this->input->post('valor_frete', true),
            'saldo_estoque' => (int)$this->input->post('saldo_estoque', true)
        ];
    }
}
