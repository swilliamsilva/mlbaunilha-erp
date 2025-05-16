<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Output $output
 */
class Errors extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('output');
    }

    public function error_404() {
        $this->output->set_status_header(404);
        $this->load->view('errors/html/error_404');
    }

    public function error_403() {
        $this->output->set_status_header(403);
        $this->load->view('errors/html/error_403');
    }

    public function error_500() {
        $this->output->set_status_header(500);
        $this->load->view('errors/html/error_500');
    }
}
