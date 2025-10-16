<?php

class Loader {

    public function __construct() {}

    public function view($view, $data = []) {
        // Construct the path to the view file within the module
        // Note: This requires the Loader to know the module path.
        // We will pass it from the controller.
        $view_path = $data['__module_path'] . 'views/' . $view . '.php';

        if (file_exists($view_path)) {
            extract($data);
            require $view_path;
        } else {
            die("View file not found: {$view_path}");
        }
    }

    public function template($view, $data = []) {
        // The template loader automatically includes header, sidebar, top_nav, and footer.
        $data['__module_path'] = $data['__module_path'] ?? ''; // Ensure it exists
        
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/top_nav', $data);

        // Main content starts here
        echo '<main id="main-container">';
        $this->view($view, $data); // Your main content view
        echo '</main>';

        $this->view('templates/footer', $data);
    }

    public function model($model, &$controller_instance) {
        $module_path = $controller_instance->getModulePath();
        $model_path = $module_path . 'models/' . $model . '.php';

        if (file_exists($model_path)) {
            require_once $model_path;
            $controller_instance->$model = new $model();
        } else {
            die("Model file not found: {$model_path}");
        }
    }
}
