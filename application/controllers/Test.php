<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB $db
 * @property CI_Output $output
 * @property CI_Loader $load
 */
class Test extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('unit_test');
        $this->load->database(); // Carrega a biblioteca de banco de dados
    }

    public function trigger_error($type) {
        try {
            switch($type) {
                case 'db':
                    $this->test_database_error();
                    break;
                case 'exception':
                    $this->test_exception();
                    break;
                case 'file':
                    $this->test_file_error();
                    break;
                default:
                    show_404();
            }
        } catch (Exception $e) {
            $this->handle_test_error($e);
        }
    }

    private function test_database_error() {
        // Força um erro de SQL
        $this->db->query('INVALID SQL COMMAND');
    }

    private function test_exception() {
        throw new RuntimeException("Exceção de teste gerada com sucesso");
    }

    private function test_file_error() {
        $this->load->helper('file');
        write_file('/caminho/invalido/test.txt', 'conteúdo teste');
    }

    private function handle_test_error($exception) {
        $response = [
            'error' => $exception->getMessage(),
            'type' => get_class($exception),
            'timestamp' => date('c')
        ];

        if (ENVIRONMENT !== 'production') {
            $response['debug'] = [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode($response));
    }
}
