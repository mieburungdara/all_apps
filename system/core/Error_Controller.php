<?php

class Error_Controller extends Controller {

    public function __construct() {
        // We need to manually set up the parent constructor
        // because this is a system controller, not a module controller.
        // We pass a dummy path and method.
        parent::__construct(APPPATH, 'index');
    }

    public function show_general($message = 'An unexpected error occurred.') {
        $this->log->error($message);
        http_response_code(500);
        
        $data['message'] = $message;
        $data['__module_path'] = APPPATH; // Provide path for the loader
        $this->load->view('errors/general', $data);
    }

    public function show_404() {
        $this->log->warning('404 Not Found: ' . $_SERVER['REQUEST_URI']);
        http_response_code(404);

        // TODO: Create a specific 404 view
        $data['message'] = 'The page you requested was not found.';
        $data['__module_path'] = APPPATH;
        $this->load->view('errors/general', $data);
    }
}
