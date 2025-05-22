<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Estoque_model $estoque
 * @property Produto_model $produto
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 */
class Estoque extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Estoque_model', 'estoque');
        $this->load->model('Produto_model', 'produto');
    }

    public function index() {
        try {
            $data = [
                'estoques' => $this->estoque->listar_todos(),
                'flash' => $this->session->flashdata()
            ];
            $this->load_template('estoque/index', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Estoque/index: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Erro ao carregar estoque');
            redirect(site_url('estoque'));
        }
    }

    public function create() {
        try {
            $data = [
                'produtos' => $this->produto->listar_ativos(),
                'flash' => $this->session->flashdata()
            ];
            $this->load_template('estoque/create', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Estoque/create: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Erro ao carregar formulário');
            redirect(site_url('estoque'));
        }
    }

    public function store() {
        try {
            $this->validar_formulario();
            $this->processar_estoque();
            
            $this->session->set_flashdata('success', 'Registro de estoque criado!');
            redirect(site_url('estoque'));
            
        } catch (Exception $e) {
            log_message('error', 'Estoque/store: ' . $e->getMessage());
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(site_url('estoque/create'));
        }
    }

    private function validar_formulario() {
        $this->form_validation->set_rules('produto_id', 'Produto', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('variacao', 'Variação', 'max_length[50]');
        $this->form_validation->set_rules('saldo', 'Saldo', 'required|integer|greater_than_equal_to[0]');
        
        if (!$this->form_validation->run()) {
            throw new RuntimeException(validation_errors('<li>', '</li>'));
        }
    }

    private function processar_estoque() {
        $dados = [
            'produto_id' => $this->input->post('produto_id', true),
            'variacao' => $this->input->post('variacao', true),
            'saldo' => (int)$this->input->post('saldo', true),
            'ultima_atualizacao' => date('Y-m-d H:i:s')
        ];
        
        if (!$this->estoque->atualizar_registro($dados)) {
            throw new RuntimeException('Falha na atualização do registro');
        }
    }
}
