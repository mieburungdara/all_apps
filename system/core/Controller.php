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

        // Superadmin has unrestricted access
        if ($this->Auth_model->user_has_role($user_id, 'superadmin')) {
            return;
        }

        foreach ($required_roles as $role) {
            if ($this->Auth_model->user_has_role($user_id, $role)) {
                return; // User has at least one of the required roles
            }
        }

        // If we get here, user does not have any of the required roles
        $this->response->redirect('/sekolah/errors/show_403');
    }

    protected function _api_auth_check() {
        $this->load->model('Users_model');
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (!$auth_header || !preg_match('/^Bearer\s+(.*)$/i', $auth_header, $matches)) {
            $this->response->set_status_code(401)->json(['error' => 'Unauthorized', 'message' => 'Authorization header missing or malformed.']);
            exit;
        }

        $api_key = $matches[1];
        $user = $this->Users_model->get_user_by_api_key($api_key);

        if (!$user) {
            $this->response->set_status_code(401)->json(['error' => 'Unauthorized', 'message' => 'Invalid API key.']);
            exit;
        }

        // Make user data available to the controller
        $this->api_user = $user;
    }
}
