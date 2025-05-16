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
class Cupons extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cupom_model', 'cupom', TRUE);
        $this->load->model('Produto_model', 'produto', TRUE);
        $this->load->model('Pedido_model', 'pedido', TRUE);
        $this->load->library(['form_validation', 'session', 'input']);
        $this->load->helper('url');
    }

    public function index() {
        try {
            $data = [
                'cupons' => $this->cupom->listar_todos(),
                'flash_message' => $this->session->flashdata()
            ];
            $this->carregar_views('cupons/index', $data);
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    public function create() {
        try {
            $data = [
                'produtos' => $this->produto->listar_ativos(),
                'pedidos' => $this->pedido->listar_recentes(),
                'flash_message' => $this->session->flashdata()
            ];
            $this->carregar_views('cupons/create', $data);
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    public function store() {
        try {
            if ($this->validar_formulario()) {
                $this->processar_cadastro();
                $this->session->set_flashdata('success', 'Cupom cadastrado com sucesso!');
                redirect('cupons');
            }
            $this->session->set_flashdata('error', validation_errors());
            $this->create();
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    private function validar_formulario() {
        $this->form_validation->set_rules('produto_id', 'Produto', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('pedido_id', 'Pedido', 'is_natural_no_zero');
        $this->form_validation->set_rules('valor_cupom', 'Valor', 'required|decimal');
        $this->form_validation->set_rules('data_validade', 'Validade', 'required|valid_date');
        
        return $this->form_validation->run();
    }

    private function processar_cadastro() {
        $dados = [
            'produto_id' => $this->input->post('produto_id', TRUE),
            'pedido_id' => $this->input->post('pedido_id', TRUE),
            'valor_cupom' => $this->input->post('valor_cupom', TRUE),
            'data_validade' => date('Y-m-d', strtotime($this->input->post('data_validade', TRUE))),
            'condicao' => $this->input->post('condicao', TRUE),
            'criado_em' => date('Y-m-d H:i:s')
        ];

        if (!$this->cupom->cadastrar($dados)) {
            throw new Exception('Falha ao registrar cupom');
        }
    }

    private function carregar_views($pagina, $data = []) {
        $data['view'] = $pagina;
        $this->load->view('templates/header', $data);
        $this->load->view($pagina, $data);
        $this->load->view('templates/footer');
    }

    private function handle_error(Exception $e) {
        log_message('error', 'Cupons: ' . $e->getMessage());
        $this->session->set_flashdata('error', 'Erro: ' . $e->getMessage());
        redirect('errors/general');
    }
}
