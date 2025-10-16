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

    public function __get($name) {
        return $this->load->$name;
    }

    protected function _auth_check() {
        if (!$this->session->get('user_id')) {
            $this->response->redirect('/sekolah/users/login');
        }
    }

    protected function _authorize(array $required_roles) {
        $this->_auth_check(); // Ensure user is logged in first

        $user_id = $this->session->get('user_id');
        $this->load->model('Auth_model');

        foreach ($required_roles as $role) {
            if ($this->Auth_model->user_has_role($user_id, $role)) {
                return; // User has at least one of the required roles
            }
        }

        // If we get here, user does not have any of the required roles
        $this->session->set_flash('error', 'You do not have permission to access this page.');
        $this->response->redirect('/sekolah/users/dashboard'); // Or a dedicated 403 page
    }
}
