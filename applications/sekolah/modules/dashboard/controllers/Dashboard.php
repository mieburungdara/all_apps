<?php

class Dashboard extends Controller {

    public function __construct($module_path) {
        parent::__construct($module_path);
        $this->load_model('Dashboard_model');
    }

    public function index() {
        $data['title'] = "Dashboard";
        $data['welcome_message'] = $this->Dashboard_model->get_welcome_message();
        $this->load_view('index', $data);
    }

}
