<?php

class Controller {

    protected $module_path;
    protected $input;
    protected $session;

    public function __construct($module_path, $called_method) {
        $this->module_path = $module_path;
        $this->input = Input::getInstance();
        $this->session = Session::getInstance();

        // Check if the called method requires authentication
        if (isset($this->protected_methods) && in_array($called_method, $this->protected_methods)) {
            $this->_auth_check();
        }
    }

    public function load_view($view, $data = []) {
        $view_path = $this->module_path . 'views/' . $view . '.php';

        if (file_exists($view_path)) {
            extract($data);
            require_once $view_path;
        } else {
            echo "View file not found: {$view_path}";
        }
    }

    public function load_model($model) {
        $model_path = $this->module_path . 'models/' . $model . '.php';

        if (file_exists($model_path)) {
            require_once $model_path;
            $this->$model = new $model();
        } else {
            echo "Model file not found: {$model_path}";
        }
    }

    protected function _auth_check() {
        if ($this->session->get('user_id') === null) {
            header('Location: /sekolah/users/login');
            exit();
        }
    }
}
