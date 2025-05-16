<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cliente_model');
        $this->load->helper(['form', 'url']);
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
        // Configurações de validação
        $this->form_validation->set_rules('cpf', 'CPF', 'required|exact_length[14]');
        $this->form_validation->set_rules('nome', 'Nome', 'required|max_length[100]');
        $this->form_validation->set_rules('cep', 'CEP', 'required|exact_length[9]');
        $this->form_validation->set_rules('telefone', 'Telefone', 'max_length[20]');
        $this->form_validation->set_rules('email', 'E-mail', 'valid_email|max_length[100]');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = [
                'cpf' => $this->input->post('cpf', TRUE),
                'nome' => $this->input->post('nome', TRUE),
                'cep' => $this->input->post('cep', TRUE),
                'telefone' => $this->input->post('telefone', TRUE),
                'email' => $this->input->post('email', TRUE),
                'observacao' => $this->input->post('observacao', TRUE)
            ];

            $this->Cliente_model->insert($data);
            redirect('clientes');
        }
    }
}
