<?php
// application/controllers/Logs.php
class Logs extends CI_Controller {
    public function index() {
        header('Content-Type: text/plain');
        echo file_get_contents('/var/www/html/application/logs/php_errors.log');
        echo "\n\n";
        echo file_get_contents('/var/log/apache2/error.log');
    }
}
