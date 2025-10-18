<?php

class Installer extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
    }

    public function index() {
        $data['title'] = 'Installer';
        $this->load->view('index', $data, 'auth');
    }

}
