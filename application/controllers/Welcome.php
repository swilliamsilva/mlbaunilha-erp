<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Inicial do Sistema
 * 
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Output $output
 * @property CI_Form_validation $form_validation
 */
class Welcome extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_maintenance_mode();
    }

    public function index() {
        try {
            $data = [
                'app_name' => 'MLBaunilha ERP',
                'version' => '1.0.0',
                'features' => $this->get_system_features()
            ];
            
            $this->load_template('welcome_message', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Welcome Controller: ' . $e->getMessage());
            $this->show_error_page();
        }
    }

    public function maintenance() {
        $this->output
            ->set_status_header(503)
            ->set_content_type('text/html')
            ->set_output($this->load->view('errors/maintenance', null, true));
    }

    public function contact() {
        try {
            if ($this->input->post()) {
                $this->process_contact_form();
            }
            
            $this->load_template('contact/form');
            
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            redirect('welcome/contact');
        }
    }

    private function check_maintenance_mode() {
        if (file_exists(APPPATH . 'config/maintenance.php')) {
            redirect('welcome/maintenance');
        }
    }

    private function get_system_features() {
        return [
            'Gestão de Pedidos',
            'Controle de Estoque',
            'Relatórios Financeiros',
            'Integração com Marketplaces'
        ];
    }

    private function process_contact_form() {
        $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
        $this->form_validation->set_rules('mensagem', 'Mensagem', 'required|min_length[10]');

        if (!$this->form_validation->run()) {
            throw new RuntimeException(validation_errors('<li>', '</li>'));
        }

        $this->send_contact_email();
    }

    private function send_contact_email() {
        // Implementação do envio de e-mail
        $this->session->set_flashdata('success', 'Mensagem enviada com sucesso!');
        redirect('welcome/contact');
    }

    private function show_error_page() {
        $this->output
            ->set_status_header(500)
            ->set_content_type('text/html')
            ->set_output($this->load->view('errors/error_general', null, true));
    }

    public function api_info() {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'app' => 'MLBaunilha ERP',
                'version' => '1.0.0',
                'environment' => ENVIRONMENT
            ]));
    }
}
