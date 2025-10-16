<?php

class Errors extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
    }

    public function show_403() {
        // Set HTTP status code
        http_response_code(403);
        $this->load->view('errors/403');
    }
    
    public function show_404() {
        // Set HTTP status code
        http_response_code(404);
        $this->load->view('errors/404', [], null);
    }
}
