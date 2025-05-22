<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller para verificação de saúde do sistema
 * 
 * @property CI_DB $db
 * @property CI_Output $output
 * @property CI_Loader $load
 */
class Health extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
        $this->load->database(); // Carrega explicitamente a biblioteca de banco de dados
    }

    public function check() {
        try {
            // Teste de conexão com banco de dados
            $this->test_database_connection();
            
            // Verificação de arquivos essenciais
            $this->verify_essential_files();
            
            // Teste de escrita em logs
            $this->test_log_writing();

            $this->send_success_response();

        } catch (Exception $e) {
            $this->handle_error($e);
        }
    }

    private function test_database_connection() {
        // Força reconexão e teste de query
        if (!$this->db->reconnect() || !$this->db->simple_query('SELECT 1')) {
            throw new Exception("Database connection failed: " . $this->db->error()['message']);
        }
    }

    private function verify_essential_files() {
        $essential_files = [
            APPPATH.'config/database.php',
            APPPATH.'models/Produto_model.php',
            APPPATH.'core/MY_Controller.php'
        ];

        foreach ($essential_files as $file) {
            if (!is_readable($file)) {
                throw new Exception("Missing or inaccessible file: " . basename($file));
            }
        }
    }

    private function test_log_writing() {
        $log_message = "Health check log test - " . date('Y-m-d H:i:s') . PHP_EOL;
        $log_path = APPPATH.'logs/healthcheck.log';
        
        if (!write_file($log_path, $log_message, 'a+')) {
            throw new Exception("Failed to write to log file: {$log_path}");
        }
    }

    private function send_success_response() {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'ok',
                'timestamp' => date('c'),
                'services' => [
                    'database' => true,
                    'filesystem' => true,
                    'logging' => true
                ],
                'system' => [
                    'php_version' => PHP_VERSION,
                    'ci_version' => CI_VERSION,
                    'environment' => ENVIRONMENT
                ]
            ]));
    }

    private function handle_error($exception) {
        log_message('error', 'Health check failed: ' . $exception->getMessage());
        
        $this->output
            ->set_status_header(500)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'code' => 'SERVICE_UNAVAILABLE',
                'message' => $exception->getMessage(),
                'timestamp' => date('c'),
                'documentation' => base_url('docs/errors/healthcheck')
            ]));
    }
}
