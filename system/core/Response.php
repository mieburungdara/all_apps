<?php

class Response {

    public function set_status_code($code) {
        http_response_code($code);
        return $this; // Allow chaining
    }

    public function set_header($name, $value) {
        header($name . ': ' . $value);
        return $this; // Allow chaining
    }

    public function redirect($uri = '') {
        header('Location: ' . $uri);
        exit();
    }

    public function json($data, $statusCode = 200) {
        $this->set_status_code($statusCode);
        $this->set_header('Content-Type', 'application/json');
        echo json_encode($data);
        exit();
    }
}