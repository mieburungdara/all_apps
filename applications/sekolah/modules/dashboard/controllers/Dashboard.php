<?php

class Dashboard extends Controller {

    protected $protected_methods = ['index'];

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->load_model('Dashboard_model');
    }

    public function index() {
        $data['title'] = "Dashboard";
        $data['welcome_message'] = $this->Dashboard_model->get_welcome_message();
        $this->load_view('index', $data);
    }

}