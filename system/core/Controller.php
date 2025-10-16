<?php

class Controller {

    protected $module_path;
    private static $instance;

    public function __construct($module_path, $called_method) {
        self::$instance = $this;
        $this->module_path = $module_path;
        
        // The Loader is the only library we need to instantiate directly.
        $this->load = new Loader();

        // Check if the called method requires authentication
        if (isset($this->protected_methods) && in_array($called_method, $this->protected_methods)) {
            $this->_auth_check();
        }
    }

    public static function &get_instance() {
        return self::$instance;
    }

    public function __get($key) {
        $this->$key = $this->load->library($key);
        return $this->$key;
    }

    public function getModulePath() {
        return $this->module_path;
    }

    protected function _auth_check() {
        if ($this->session->get('user_id') === null) {
            // Use the response library for redirection
            $this->response->redirect('/sekolah/users/login');
        }
    }
}
