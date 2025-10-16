<?php

class Router {

    protected $module = 'dashboard';
    protected $controller = 'Dashboard';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $this->parseUrl();
        $this->dispatch();
    }

    public function parseUrl() {
        if (isset($_SERVER['QUERY_STRING'])) {
            $url = trim($_SERVER['QUERY_STRING'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $segments = explode('/', $url);

            if (!empty($segments[0])) {
                $this->module = $segments[0];
                $this->controller = ucfirst($segments[0]);
            }

            if (!empty($segments[1])) {
                $this->method = $segments[1];
            }

            if (!empty(array_slice($segments, 2))) {
                $this->params = array_slice($segments, 2);
            }
        }
    }

    public function dispatch() {
        $module_path = APPPATH . 'modules/' . $this->module . '/';
        $controller_path = $module_path . 'controllers/' . $this->controller . '.php';

        if (file_exists($controller_path)) {
            require_once $controller_path;

            if (class_exists($this->controller)) {
                $controller_instance = new $this->controller($module_path, $this->method);

                if (method_exists($controller_instance, $this->method)) {
                    call_user_func_array([$controller_instance, $this->method], $this->params);
                } else {
                    echo "Method '{$this->method}' not found in controller '{$this->controller}'.";
                }
            } else {
                echo "Controller class '{$this->controller}' not found.";
            }
        } else {
            // Default to dashboard if controller not found
            $this->loadDefaultController();
        }
    }

    public function loadDefaultController() {
        $module_path = APPPATH . 'modules/dashboard/';
        $controller_path = $module_path . 'controllers/Dashboard.php';

        if (file_exists($controller_path)) {
            require_once $controller_path;
            $controller_instance = new Dashboard($module_path, 'index');
            $controller_instance->index();
        } else {
            echo "Default controller not found!";
        }
    }
}