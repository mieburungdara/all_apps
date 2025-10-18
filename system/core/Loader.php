<?php

class Loader {

    private $_loaded_libraries = [];

    public function __construct() {}

    public function library($library) {
        $library = ucfirst($library);
        if (isset($this->_loaded_libraries[$library])) {
            return $this->_loaded_libraries[$library];
        }

        $file_path = SYSPATH . 'core/' . $library . '.php';
        if (!file_exists($file_path)) {
            show_error("Core library not found: {$library}.php");
        }
        require_once $file_path;

        // Handle singletons vs regular classes
        if (method_exists($library, 'getInstance')) {
            $instance = $library::getInstance();
        } else {
            $instance = new $library();
        }

        $this->_loaded_libraries[$library] = $instance;
        return $instance;
    }

    public function view($view, $data = [], $template = 'main') {
        $view_path = $this->module_path . 'views/' . $view . '.php';

        if (file_exists($view_path)) {
            extract($data);

            if ($template === 'main') {
                require_once APPPATH . 'views/template/header.php';
                require_once APPPATH . 'views/template/sidebar.php';
                require_once APPPATH . 'views/template/topbar.php';
                require_once $view_path;
                require_once APPPATH . 'views/template/footer.php';
            } elseif ($template === 'auth') {
                require_once APPPATH . 'views/template/auth_header.php';
                require_once $view_path;
                require_once APPPATH . 'views/template/auth_footer.php';
            } else { // No template
                require_once $view_path;
            }
        } else {
            show_error("Unable to load the requested file: " . $view . ".php");
        }
    }

    public function template($view, $data = []) {
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/top_nav', $data);

        echo '<main id="main-container">';
        $this->view($view, $data); 
        echo '</main>';

        $this->view('templates/footer', $data);
    }

    public function model($model_name) {
        $CI =& Controller::get_instance();
        $model_path = $CI->module_path . 'models/' . $model_name . '.php';

        error_log("DEBUG: Loader::model() checking path: {$model_path}", 3, __DIR__ . '/../../php_server.log');
        $file_exists_result = file_exists($model_path);
        error_log("DEBUG: file_exists({$model_path}) returned: " . ($file_exists_result ? 'true' : 'false'), 3, __DIR__ . '/../../php_server.log');


        if ($file_exists_result) {
            require_once $model_path;
            $CI->$model_name = new $model_name();
        } else {
            show_error("Model file not found: {$model_path}");
        }
    }
}