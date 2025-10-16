<?php

class Dashboard extends Controller {

    protected $protected_methods = ['index'];

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->load->model('Dashboard_model', $this);
    }

    public function index() {
        $data['page_title'] = "Dashboard Sekolah";
        $data['user_nama'] = $this->session->get('user_nama'); // Get user name from session
        $data['welcome_message'] = $this->Dashboard_model->get_welcome_message();
        
        // Pass the module path to the data array for the loader to use
        $data['__module_path'] = $this->module_path;

        $this->load->template('dashboard/index', $data);
    }

}
