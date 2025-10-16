<?php

class Config {

    private static $instance = null;
    private $configs = [];

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function load($file) {
        // Check if already loaded
        if (isset($this->configs[$file])) {
            return $this->configs[$file];
        }

        $config_path = APPPATH . 'config/' . $file . '.php';

        if (file_exists($config_path)) {
            $config_data = require $config_path;
            $this->configs[$file] = $config_data;
            return $config_data;
        } else {
            die("Configuration file not found: {$config_path}");
        }
    }
}
