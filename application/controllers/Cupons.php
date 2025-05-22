<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Cupom_model $cupom
 * @property Produto_model $produto
 * @property Pedido_model $pedido
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 */
class Cupons extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cupom_model', 'cupom');
        $this->load->model('Produto_model', 'produto');
        $this->load->model('Pedido_model', 'pedido');
    }

    public function index() {
        try {
            $data = [
                'cupons' => $this->cupom->listar_todos(),
                'flash' => $this->session->flashdata()
            ];
            $this->load_template('cupons/index', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Erro em Cupons/index: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Erro ao listar cupons');
            redirect(site_url('cupons'));
        }
    }

    public function create() {
        try {
            $data = [
                'produtos' => $this->produto->listar_ativos(),
                'pedidos' => $this->pedido->listar_recentes(),
                'flash' => $this->session->flashdata()
            ];
            $this->load_template('cupons/create', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Erro em Cupons/create: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Erro ao carregar formulário');
            redirect(site_url('cupons'));
        }
    }

    public function store() {
        try {
            $this->validar_formulario();
            $this->processar_cadastro();
            
            $this->session->set_flashdata('success', 'Cupom cadastrado com sucesso!');
            redirect(site_url('cupons'));
            
        } catch (Exception $e) {
            log_message('error', 'Erro em Cupons/store: ' . $e->getMessage());
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(site_url('cupons/create'));
        }
    }

    private function validar_formulario() {
        $this->form_validation->set_rules('produto_id', 'Produto', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('pedido_id', 'Pedido', 'is_natural_no_zero');
        $this->form_validation->set_rules('valor_cupom', 'Valor', 'required|decimal');
        $this->form_validation->set_rules('data_validade', 'Validade', 'required|callback_validar_data');
        
        if (!$this->form_validation->run()) {
            throw new Exception(validation_errors('<li>', '</li>'));
        }
    }

    public function validar_data($data) {
        if (strtotime($data) < strtotime('today')) {
            $this->form_validation->set_message('validar_data', 'A data de validade deve ser futura');
            return false;
        }
        return true;
    }

    private function processar_cadastro() {
        $dados = [
            'produto_id' => $this->input->post('produto_id', true),
            'pedido_id' => $this->input->post('pedido_id', true) ?: null,
            'valor_cupom' => (float)$this->input->post('valor_cupom', true),
            'data_validade' => date('Y-m-d', strtotime($this->input->post('data_validade', true))),
            'condicao' => $this->input->post('condicao', true),
            'criado_em' => date('Y-m-d H:i:s')
        ];

        if (!$this->cupom->cadastrar($dados)) {
            throw new Exception('Falha ao registrar no banco de dados');
        }
    }
}
