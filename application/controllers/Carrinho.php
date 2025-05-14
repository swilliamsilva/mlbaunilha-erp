// application/controllers/Carrinho.php
class Carrinho extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Produto_model');
    }

    public function index() {
        $data['itens'] = $this->session->userdata('carrinho') ?? [];
        $data['subtotal'] = array_reduce($data['itens'], function($total, $item) {
            return $total + $item['quantidade'] * $item['preco'];
        }, 0);

        if ($data['subtotal'] > 200) {
            $data['frete'] = 0;
        } elseif ($data['subtotal'] >= 52 && $data['subtotal'] <= 166.59) {
            $data['frete'] = 15;
        } else {
            $data['frete'] = 20;
        }

        $data['total'] = $data['subtotal'] + $data['frete'];

        $this->load->view('templates/header');
        $this->load->view('carrinho/index', $data);
        $this->load->view('templates/footer');
    }

    public function adicionar($produto_id) {
        $produto = $this->Produto_model->get_by_id($produto_id);
        $quantidade = $this->input->post('quantidade');

        $item = [
            'id' => $produto->id,
            'nome' => $produto->nome,
            'preco' => $produto->preco_venda,
            'quantidade' => $quantidade
        ];

        $carrinho = $this->session->userdata('carrinho') ?? [];
        $carrinho[] = $item;
        $this->session->set_userdata('carrinho', $carrinho);

        redirect('carrinho');
    }

    public function limpar() {
        $this->session->unset_userdata('carrinho');
        redirect('carrinho');
    }
}
