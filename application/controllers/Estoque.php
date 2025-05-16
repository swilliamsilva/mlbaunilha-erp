<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Estoque_model $estoque
 * @property Produto_model $produto
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 */
class Estoque extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        // Carregar dependências corretamente
        $this->load->model('Estoque_model', 'estoque');
        $this->load->model('Produto_model', 'produto');
        $this->load->library(['form_validation', 'session', 'input']);
    }

    public function index() {
        try {
            $data = [
                'estoques' => $this->estoque->listar_todos(),
                'flash_message' => $this->session->flashdata()
            ];
            $this->carregar_views('estoque/index', $data);
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    public function create() {
        try {
            $data = [
                'produtos' => $this->produto->listar_ativos(),
                'flash_message' => $this->session->flashdata()
            ];
            $this->carregar_views('estoque/create', $data);
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    public function store() {
        try {
            if ($this->validar_formulario()) {
                $this->processar_estoque();
                $this->session->set_flashdata('success', 'Registro de estoque criado!');
                redirect('estoque');
            }
            $this->session->set_flashdata('error', validation_errors('<div class="error">', '</div>'));
            redirect('estoque/create');
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    private function validar_formulario() {
        $this->form_validation->set_rules('produto_id', 'Produto', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('variacao', 'Variação', 'max_length[50]');
        $this->form_validation->set_rules('saldo', 'Saldo', 'required|integer');
        
        return $this->form_validation->run();
    }

    private function processar_estoque() {
        $dados = [
            'produto_id' => $this->input->post('produto_id', TRUE),
            'variacao' => $this->input->post('variacao', TRUE),
            'saldo' => $this->input->post('saldo', TRUE),
            'ultima_atualizacao' => date('Y-m-d H:i:s')
        ];
        
        if (!$this->estoque->atualizar_registro($dados)) {
            throw new Exception('Falha na atualização do estoque');
        }
    }

    private function carregar_views($pagina, $data = []) {
        $data['view'] = $pagina;
        $this->load->view('templates/header', $data);
        $this->load->view($pagina, $data);
        $this->load->view('templates/footer');
    }

    private function handle_error(Exception $e) {
        log_message('error', 'Estoque: ' . $e->getMessage());
        $this->session->set_flashdata('error', 'Erro: ' . $e->getMessage());
        redirect('errors/general');
    }
}
