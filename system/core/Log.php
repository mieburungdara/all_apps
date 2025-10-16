<?php

class Log {

    private static $instance = null;
    private $log_path;

    private function __construct() {
        // We will define LOG_PATH in a config file later
        $this->log_path = realpath(__DIR__ . '/../../logs') . '/';
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Log();
        }
        return self::$instance;
    }

    public function log($level, $message) {
        $level = strtoupper($level);
        $filepath = $this->log_path . 'log-' . date('Y-m-d') . '.log';
        $log_message = "";

        // Get caller information
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $caller = $backtrace[1] ?? null; // 0 is current, 1 is the caller

        $file = $caller['file'] ?? 'unknown_file';
        $line = $caller['line'] ?? 'unknown_line';
        $function = $caller['function'] ?? 'unknown_function';

        if (!file_exists($filepath)) {
            $log_message .= "<" . "?php defined('SYSPATH') OR exit('No direct script access allowed.'); ?" . ">\n\n";
        }

        $log_message .= $level . ' - ' . date('Y-m-d H:i:s') . ' - File: ' . $file . ' Line: ' . $line . ' Func: ' . $function . ' --> ' . $message . "\n";

        file_put_contents($filepath, $log_message, FILE_APPEND);
    }

    public function info($message) {
        $this->log('INFO', $message);
    }

    public function warning($message) {
        $this->log('WARNING', $message);
    }

    public function error($message) {
        $this->log('ERROR', $message);
    }
}

