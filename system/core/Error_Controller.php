<?php

class Error_Controller {

    public function show_general($message = 'An unexpected error occurred.') {
        // In the future, we can load a proper view
        // For now, a simple formatted error is enough
        http_response_code(500);
        echo "<div style='border: 1px solid #ff0000; padding: 10px; margin: 10px; background-color: #ffecec;'>";
        echo "<h1>An Error Was Encountered</h1>";
        echo "<p>Message: " . htmlspecialchars($message) . "</p>";
        echo "</div>";
    }

    public function show_404() {
        http_response_code(404);
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
        echo "<h1>404 Not Found</h1>";
        echo "<p>The page you requested was not found.</p>";
        echo "</div>";
    }
}
