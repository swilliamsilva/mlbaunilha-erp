<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property Produto_model $produto
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 */
class Carrinho extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(['session', 'form_validation']);
        $this->load->model('Produto_model', 'produto');
        $this->load->helper('url');
    }

    public function index() {
        $data = [
            'itens' => $this->session->userdata('carrinho') ?? [],
            'subtotal' => 0,
            'frete' => 20,
            'total' => 0
        ];

        $data['subtotal'] = array_reduce($data['itens'], function($total, $item) {
            return $total + ($item['quantidade'] * $item['preco']);
        }, 0);

        $data['frete'] = $this->calcular_frete($data['subtotal']);
        $data['total'] = $data['subtotal'] + $data['frete'];

        $this->carregar_views('carrinho/index', $data);
    }

    public function adicionar($produto_id) {
        $this->validar_quantidade();
        $produto = $this->produto->obter_por_id($produto_id);
        $this->adicionar_item_carrinho($produto);
        redirect('carrinho');
    }

    public function limpar() {
        $this->session->unset_userdata('carrinho');
        redirect('carrinho');
    }

    private function carregar_views($pagina, $data) {
        $this->load->view('templates/header', $data);
        $this->load->view($pagina, $data);
        $this->load->view('templates/footer');
    }

    private function calcular_frete($subtotal) {
        if ($subtotal > 200) return 0;
        if ($subtotal >= 52 && $subtotal <= 166.59) return 15;
        return 20;
    }

    private function validar_quantidade() {
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'required|is_natural_no_zero');
        if (!$this->form_validation->run()) {
            throw new Exception(validation_errors());
        }
    }

    private function adicionar_item_carrinho($produto) {
        $item = [
            'id' => $produto->id,
            'nome' => $produto->nome,
            'preco' => $produto->preco_venda,
            'quantidade' => $this->input->post('quantidade', TRUE)
        ];

        $carrinho = $this->session->userdata('carrinho') ?? [];
        $carrinho[] = $item;
        $this->session->set_userdata('carrinho', $carrinho);
    }
	public function atualizar($produto_id) {
		// Lógica para atualizar quantidade
	}
	
	public function remover($produto_id) {
		// Lógica para remover item
	}
}
