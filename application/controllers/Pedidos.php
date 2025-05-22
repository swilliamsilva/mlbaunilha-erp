<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller de Gestão de Pedidos
 * 
 * @property Pedido_model $pedido
 * @property Cliente_model $cliente
 * @property Produto_model $produto
 * @property Cupom_model $cupom
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 */
class Pedidos extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model([
            'Pedido_model' => 'pedido',
            'Cliente_model' => 'cliente',
            'Produto_model' => 'produto',
            'Cupom_model' => 'cupom'
        ]);
    }

    public function index() {
        try {
            $data = [
                'pedidos' => $this->pedido->listar_todos(),
                'flash' => $this->session->flashdata()
            ];
            $this->load_template('pedidos/index', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Pedidos/index: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Erro ao carregar pedidos');
            redirect(site_url('pedidos'));
        }
    }

    public function create() {
        try {
            $data = array_merge(
                $this->preparar_dados_formulario(),
                ['flash' => $this->session->flashdata()]
            );
            $this->load_template('pedidos/create', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Pedidos/create: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Erro ao carregar formulário');
            redirect(site_url('pedidos'));
        }
    }

    public function store() {
        try {
            $this->validar_formulario();
            $this->processar_pedido();
            
            $this->session->set_flashdata('success', 'Pedido criado com sucesso!');
            redirect(site_url('pedidos'));
            
        } catch (Exception $e) {
            log_message('error', 'Pedidos/store: ' . $e->getMessage());
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(site_url('pedidos/create'));
        }
    }

    public function view($id) {
        try {
            $this->validar_id($id);
            $data = [
                'pedido' => $this->pedido->obter_por_id($id),
                'flash' => $this->session->flashdata()
            ];
            $this->load_template('pedidos/view', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Pedidos/view: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Erro ao visualizar pedido');
            redirect(site_url('pedidos'));
        }
    }

    public function cancelar($id) {
        try {
            $this->validar_id($id);
            
            if ($this->pedido->atualizar_status($id, 'cancelado')) {
                $this->session->set_flashdata('success', 'Pedido cancelado com sucesso!');
                redirect(site_url('pedidos'));
            }
            throw new Exception('Falha ao cancelar pedido');
            
        } catch (Exception $e) {
            log_message('error', 'Pedidos/cancelar: ' . $e->getMessage());
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(site_url('pedidos'));
        }
    }

    private function preparar_dados_formulario() {
        return [
            'clientes' => $this->cliente->listar_todos(),
            'produtos' => $this->produto->listar_com_precos(),
            'cupons' => $this->cupom->listar_ativos()
        ];
    }

    private function validar_formulario() {
        $this->form_validation->set_rules('cliente_id', 'Cliente', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('produto_id', 'Produto', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('quantidade_solicitada', 'Quantidade', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('data_entrega_prevista', 'Data Prevista', 'required|callback_validar_data');

        if (!$this->form_validation->run()) {
            throw new RuntimeException(validation_errors('<li>', '</li>'));
        }
    }

    public function validar_data($data) {
        if (strtotime($data) < strtotime('today')) {
            $this->form_validation->set_message('validar_data', 'A data deve ser futura');
            return false;
        }
        return true;
    }

    private function processar_pedido() {
        $dados = $this->montar_dados_pedido();
        
        if (!$this->pedido->registrar($dados)) {
            throw new RuntimeException('Falha no registro do pedido');
        }
    }

    private function montar_dados_pedido() {
        $produto_id = $this->input->post('produto_id', true);
        
        return [
            'cliente_id' => $this->input->post('cliente_id', true),
            'produto_id' => $produto_id,
            'quantidade_solicitada' => (int)$this->input->post('quantidade_solicitada', true),
            'preco_unitario' => $this->produto->obter_preco($produto_id),
            'valor_frete' => $this->calcular_frete(),
            'data_entrega_prevista' => date('Y-m-d', strtotime($this->input->post('data_entrega_prevista', true))),
            'status' => 'pendente',
            'criado_em' => date('Y-m-d H:i:s')
        ];
    }

    private function calcular_frete() {
        $quantidade = (int)$this->input->post('quantidade_solicitada', true);
        $preco = $this->produto->obter_preco($this->input->post('produto_id', true));
        $subtotal = $quantidade * $preco;

        if ($subtotal > 200) {
            return 0;
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15;
        } else {
            return 20;
        }
    }

    private function validar_id($id) {
        if (!ctype_digit((string)$id)) {
            throw new InvalidArgumentException('ID inválido');
        }
    }
}
