<?php

class Router {

    protected $module = 'dashboard';
    protected $controller = 'Dashboard';
    protected $method = 'index';
    protected $params = [];
    protected $routes = [];
    protected $base_path = ''; // New property to store the base path

    public function __construct($base_path = '') { // Accept base_path in constructor
        $this->base_path = $base_path; // Store the base path
        $this->load_routes();
        $this->parseUrl();
        $this->dispatch();
    }

    private function load_routes() {
        $config = Config::getInstance();
        $this->routes = $config->load('routes');
    }

    public function parseUrl() {
        $url = $_SERVER['REQUEST_URI'] ?? '';

        // Remove the base path from the URL
        if ($this->base_path && strpos($url, $this->base_path) === 0) {
            $url = substr($url, strlen($this->base_path));
        }

        // Remove query string
        $url_parts = explode('?', $url);
        $url = $url_parts[0];

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
        } // This closes the if (!empty($segments[0])) block
    } // This closes the parseUrl() method

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
                    $log = Log::getInstance();
                    $log->error("404 Not Found: Method '{$this->method}' not found in controller '{$this->controller}'.");
                    show_404();
                }
            } else {
                $log = Log::getInstance();
                $log->error("404 Not Found: Controller class '{$this->controller}' not found in file '{$controller_path}'.");
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
