<?php

class Config {

    private static $instance = null;
    private $_loaded_files = [];
    private $_config = [];

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function load($file) {
        if (isset($this->_loaded_files[$file])) {
            return $this->_loaded_files[$file];
        }

        $config_path = APPPATH . 'config/' . $file . '.php';

        if (file_exists($config_path)) {
            $config_data = require $config_path;
            $this->_loaded_files[$file] = $config_data;
            $this->_config = array_merge($this->_config, $config_data);
            return $config_data;
        } else {
            show_error("Configuration file not found: {$config_path}");
        }
    }

    public function item($key, $default = null) {
        return $this->_config[$key] ?? $default;
    }

    public function set_item($key, $value) {
        $this->_config[$key] = $value;
    }
}