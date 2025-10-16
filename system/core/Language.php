<?php

class Language {

    private static $instance = null;
    private $lines = [];

    private function __construct() {
        $config = Config::getInstance();
        $app_config = $config->load('app');
        $language = $app_config['language'] ?? 'en'; // Default to English if not set

        $lang_file = APPPATH . 'language/' . $language . '/validation_lang.php';

        if (file_exists($lang_file)) {
            $this->lines = require $lang_file;
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Language();
        }
        return self::$instance;
    }

    public function line($key) {
        return $this->lines[$key] ?? 'Error message not found for: ' . $key;
    }
}
