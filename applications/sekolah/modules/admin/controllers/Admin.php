<?php

class Admin extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->_authorize(['admin']);
    }

    public function index() {
        $this->load->view('admin/dashboard');
    }
}
