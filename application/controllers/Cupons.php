// application/controllers/Cupons.php
class Cupons extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Cupom_model');
        $this->load->model('Produto_model');
        $this->load->model('Pedido_model');
    }

    public function index() {
        $data['cupons'] = $this->Cupom_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('cupons/index', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $data['produtos'] = $this->Produto_model->get_all();
        $data['pedidos'] = $this->Pedido_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('cupons/create', $data);
        $this->load->view('templates/footer');
    }

    public function store() {
        $data = array(
            'produto_id' => $this->input->post('produto_id'),
            'pedido_id' => $this->input->post('pedido_id'),
            'valor_cupom' => $this->input->post('valor_cupom'),
            'data_validade' => $this->input->post('data_validade'),
            'condicao' => $this->input->post('condicao')
        );

        $this->Cupom_model->insert($data);
        redirect('cupons');
    }
}
