<?php

class Router {

    protected $module = 'dashboard';
    protected $controller = 'Dashboard';
    protected $method = 'index';
    protected $params = [];
    protected $routes = [];

    public function __construct() {
        $this->load_routes();
        $this->parseUrl();
        $this->dispatch();
    }

    private function load_routes() {
        $config = Config::getInstance();
        $this->routes = $config->load('routes');
    }

    public function parseUrl() {
        $url = $_SERVER['QUERY_STRING'] ?? '';

        // Check for custom route
        if (isset($this->routes[$url])) {
            $url = $this->routes[$url];
        }

        $url = trim($url, '/');
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
                    show_404();
                }
            } else {
                show_404();
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
            show_error('Default controller (Dashboard) not found.');
        }
    }
}
