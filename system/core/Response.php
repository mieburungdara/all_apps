<?php

class Response {

    public function redirect($uri = '') {
        // For now, we assume the URI is relative to the app root
        // We can make this smarter later
        header('Location: ' . $uri);
        exit();
    }

    public function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
