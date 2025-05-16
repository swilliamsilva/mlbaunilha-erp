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
class Pedidos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Carregamento otimizado de dependências
        $this->load->model('Pedido_model', 'pedido', TRUE);
        $this->load->model('Cliente_model', 'cliente', TRUE);
        $this->load->model('Produto_model', 'produto', TRUE);
        $this->load->model('Cupom_model', 'cupom', TRUE);
        
        $this->load->library(['form_validation', 'session']);
    }

    public function index() {
        try {
            $data['pedidos'] = $this->pedido->listar_todos();
            $this->carregar_views('pedidos/index', $data);
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    public function create() {
        try {
            $data = $this->preparar_dados_formulario();
            $this->carregar_views('pedidos/create', $data);
        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    public function store() {
        try {
            if ($this->validar_formulario()) {
                $this->processar_pedido();
                $this->session->set_flashdata('success', 'Pedido criado com sucesso!');
                redirect('pedidos');
            }
            $this->create();
        } catch (Exception $e) {
            $this->handle_error($e);
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
        $this->form_validation->set_rules('quantidade_solicitada', 'Quantidade', 'required|is_natural_no_zero|max_length[5]');
        $this->form_validation->set_rules('data_entrega_prevista', 'Data Prevista', 'required|valid_date');

        return $this->form_validation->run();
    }

    private function processar_pedido() {
        $dados = $this->montar_dados_pedido();
        $this->pedido->registrar($dados);
    }

    private function montar_dados_pedido() {
        return [
            'cliente_id' => $this->input->post('cliente_id', TRUE),
            'produto_id' => $this->input->post('produto_id', TRUE),
            'quantidade_solicitada' => $this->input->post('quantidade_solicitada', TRUE),
            'preco_unitario' => $this->produto->obter_preco($this->input->post('produto_id', TRUE)),
            'valor_frete' => $this->calcular_frete(),
            'data_entrega_prevista' => $this->input->post('data_entrega_prevista', TRUE),
            'status' => 'pendente',
            'criado_em' => date('Y-m-d H:i:s')
        ];
    }

    private function calcular_frete() {
        $quantidade = $this->input->post('quantidade_solicitada', TRUE);
        $preco = $this->produto->obter_preco($this->input->post('produto_id', TRUE));
        $subtotal = $quantidade * $preco;

        if ($subtotal > 200) return 0;
        if ($subtotal >= 52 && $subtotal <= 166.59) return 15;
        return 20;
    }

    private function carregar_views($pagina, $data = null) {
        $this->load->view('templates/header', $data);
        $this->load->view($pagina, $data);
        $this->load->view('templates/footer');
    }

    private function handle_error($exception) {
        log_message('error', $exception->getMessage());
        $this->session->set_flashdata('error', 'Ocorreu um erro inesperado');
        redirect('errors/general');
    }
	public function view($id) {
		// Lógica para visualização detalhada
	}
	
	public function cancelar($id) {
		// Lógica para cancelamento
	}
}
