<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Produto_model $produto
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property CI_Session $session
 */
class Produtos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Carregar dependências
        $this->load->model('Produto_model', 'produto', TRUE);
        $this->load->library(['form_validation', 'session', 'input']);
        $this->load->helper(['form', 'url']);
    }

    public function index() {
        try {
            $data = [
                'produtos' => $this->produto->listar_todos(),
                'flash_message' => $this->session->flashdata()
            ];
            $this->carregar_views('produtos/index', $data);
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    public function create() {
        try {
            $data['flash_message'] = $this->session->flashdata();
            $this->carregar_views('produtos/create', $data);
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    public function store() {
        try {
            if ($this->validar_formulario()) {
                $this->processar_cadastro();
                $this->session->set_flashdata('success', 'Produto cadastrado com sucesso!');
                redirect('produtos');
            }
            $this->session->set_flashdata('error', validation_errors());
            $this->create();
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    private function validar_formulario() {
        $this->form_validation->set_rules('codigo_produto', 'Código', 'required|is_unique[produtos.codigo_produto]');
        $this->form_validation->set_rules('nome', 'Nome', 'required|max_length[100]');
        $this->form_validation->set_rules('preco_compra', 'Preço de Compra', 'required|decimal');
        $this->form_validation->set_rules('preco_venda', 'Preço de Venda', 'required|decimal');
        
        return $this->form_validation->run();
    }

    private function processar_cadastro() {
        $dados = $this->montar_dados_produto();
        if (!$this->produto->cadastrar($dados)) {
            throw new Exception('Falha ao salvar no banco de dados');
        }
    }

    private function montar_dados_produto() {
        return [
            'codigo_produto' => $this->input->post('codigo_produto', TRUE),
            'nome' => $this->input->post('nome', TRUE),
            'descricao' => $this->input->post('descricao', TRUE),
            'link_ml' => $this->input->post('link_ml', TRUE),
            'preco_compra' => (float)$this->input->post('preco_compra', TRUE),
            'cupom_desconto' => $this->input->post('cupom_desconto', TRUE),
            'preco_venda' => (float)$this->input->post('preco_venda', TRUE),
            'valor_frete' => (float)$this->input->post('valor_frete', TRUE),
            'saldo_estoque' => (int)$this->input->post('saldo_estoque', TRUE)
        ];
    }

    private function carregar_views($pagina, $data = []) {
        $data['view'] = $pagina;
        $this->load->view('templates/header', $data);
        $this->load->view($pagina, $data);
        $this->load->view('templates/footer');
    }

    private function handle_error(Exception $e) {
        log_message('error', 'ERRO: ' . $e->getMessage());
        $this->session->set_flashdata('error', 'Erro: ' . $e->getMessage());
        redirect('errors/general');
    }
}
