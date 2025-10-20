<?php

class Loader {

    private $_loaded_libraries = [];
    private $module_path; // New property to store the module path

    public function __construct($module_path = '') {
        $this->module_path = $module_path;
    }

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

        $model_file_name = basename($model_name);

        if (strpos($model_name, '/') !== false) {
            // Path includes module: 'module/model'
            $path_parts = explode('/', $model_name);
            $module = $path_parts[0];
            $model_file_name = $path_parts[1];
            $model_path = APPPATH . 'modules/' . $module . '/models/' . $model_file_name . '.php';
        } else {
            // Load from the current module
            $model_path = $this->module_path . 'models/' . $model_name . '.php';
        }

        if (file_exists($model_path)) {
            require_once $model_path;
            $CI->$model_file_name = new $model_file_name();
        } else {
            show_error("Model file not found: {$model_path}");
        }
    }
}