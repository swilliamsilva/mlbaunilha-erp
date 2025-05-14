// application/controllers/Clientes.php
class Clientes extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Cliente_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    public function index() {
        $data['clientes'] = $this->Cliente_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('clientes/index', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $this->load->view('templates/header');
        $this->load->view('clientes/create');
        $this->load->view('templates/footer');
    }

    public function store() {
        $this->form_validation->set_rules('cpf', 'CPF', 'required|exact_length[14]');
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('cep', 'CEP', 'required|exact_length[9]');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        $data = array(
            'cpf' => $this->input->post('cpf'),
            'nome' => $this->input->post('nome'),
            'cep' => $this->input->post('cep'),
            'telefone' => $this->input->post('telefone'),
            'email' => $this->input->post('email'),
            'observacao' => $this->input->post('observacao'),
            'rua' => $this->input->post('rua'),
            'bairro' => $this->input->post('bairro'),
            'cidade' => $this->input->post('cidade'),
            'estado' => $this->input->post('estado')
        );

        $this->Cliente_model->insert($data);
        redirect('clientes');
    }
}
