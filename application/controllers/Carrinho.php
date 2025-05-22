<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property Produto_model $produto
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 */
class Carrinho extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Produto_model', 'produto');
        $this->load->library('form_validation');
    }

    public function index() {
        try {
            $data = $this->calcular_totais();
            $this->load_template('carrinho/index', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Erro no carrinho: ' . $e->getMessage());
            show_error('Ocorreu um erro inesperado. Tente novamente mais tarde.', 500);
        }
    }

    public function adicionar($produto_id) {
        try {
            $this->validar_quantidade();
            $produto = $this->buscar_produto($produto_id);
            $this->adicionar_item_carrinho($produto);
            redirect(site_url('carrinho'));
            
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(site_url('carrinho'));
        }
    }

    public function limpar() {
        try {
            $this->session->unset_userdata('carrinho');
            redirect(site_url('carrinho'));
            
        } catch (Exception $e) {
            log_message('error', 'Erro ao limpar carrinho: ' . $e->getMessage());
            show_error('Erro ao limpar o carrinho', 500);
        }
    }

    public function atualizar($produto_id) {
        try {
            $this->validar_quantidade();
            $quantidade = $this->input->post('quantidade', true);
            $this->atualizar_item_carrinho($produto_id, $quantidade);
            redirect(site_url('carrinho'));
            
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(site_url('carrinho'));
        }
    }

    public function remover($produto_id) {
        try {
            $this->remover_item_carrinho($produto_id);
            redirect(site_url('carrinho'));
            
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(site_url('carrinho'));
        }
    }

    private function calcular_totais() {
        $itens = $this->session->userdata('carrinho') ?? [];
        
        return [
            'itens' => $itens,
            'subtotal' => $this->calcular_subtotal($itens),
            'frete' => $this->calcular_frete(),
            'total' => $this->calcular_total()
        ];
    }

    private function buscar_produto($produto_id) {
        $produto = $this->produto->obter_por_id($produto_id);
        
        if (!$produto) {
            throw new Exception('Produto não encontrado');
        }
        return $produto;
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
            'quantidade' => $this->input->post('quantidade', true)
        ];

        $carrinho = $this->session->userdata('carrinho') ?? [];
        $carrinho[] = $item;
        $this->session->set_userdata('carrinho', $carrinho);
    }

    private function atualizar_item_carrinho($produto_id, $quantidade) {
        $carrinho = array_map(function($item) use ($produto_id, $quantidade) {
            if ($item['id'] == $produto_id) {
                $item['quantidade'] = $quantidade;
            }
            return $item;
        }, $this->session->userdata('carrinho') ?? []);

        $this->session->set_userdata('carrinho', $carrinho);
    }

    private function remover_item_carrinho($produto_id) {
        $carrinho = array_filter($this->session->userdata('carrinho') ?? [], function($item) use ($produto_id) {
            return $item['id'] != $produto_id;
        });

        $this->session->set_userdata('carrinho', $carrinho);
    }

    private function calcular_subtotal($itens) {
        return array_reduce($itens, function($total, $item) {
            return $total + ($item['quantidade'] * $item['preco']);
        }, 0);
    }

    private function calcular_frete() {
        $subtotal = $this->calcular_subtotal($this->session->userdata('carrinho') ?? []);
        
        if ($subtotal > 200) return 0;
        if ($subtotal >= 52 && $subtotal <= 166.59) return 15;
        return 20;
    }

    private function calcular_total() {
        $subtotal = $this->calcular_subtotal($this->session->userdata('carrinho') ?? []);
        $frete = $this->calcular_frete();
        return $subtotal + $frete;
    }
}
