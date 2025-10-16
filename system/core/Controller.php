<?php

class Controller {

    protected $module_path;
    protected $input;
    protected $session;
    public $load;

    public function __construct($module_path, $called_method) {
        $this->module_path = $module_path;
        
        // Instantiate core libraries
        $this->input = Input::getInstance();
        $this->session = Session::getInstance();
        $this->load = new Loader();

        // Check if the called method requires authentication
        if (isset($this->protected_methods) && in_array($called_method, $this->protected_methods)) {
            $this->_auth_check();
        }
    }

    // This is needed by the Loader to find module-specific models
    public function getModulePath() {
        return $this->module_path;
    }

    protected function _auth_check() {
        if ($this->session->get('user_id') === null) {
            header('Location: /sekolah/users/login');
            exit();
        }
    }
}