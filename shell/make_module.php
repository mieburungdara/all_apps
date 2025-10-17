<?php

if ($argc < 2) {
    echo "Usage: php shell/make_module.php <module_name>\n";
    exit(1);
}

$moduleName = $argv[1];
$ucModuleName = ucfirst($moduleName);

// Create controller
$controllerPath = "applications/rumahsakit/controllers/{$ucModuleName}.php";
if (!is_dir(dirname($controllerPath))) {
    mkdir(dirname($controllerPath), 0755, true);
}
$controllerContent = "<?php

class {$ucModuleName} extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // Your code here
    }
}
";
file_put_contents($controllerPath, $controllerContent);

// Create model
$modelPath = "applications/rumahsakit/models/{$ucModuleName}_model.php";
if (!is_dir(dirname($modelPath))) {
    mkdir(dirname($modelPath), 0755, true);
}
$modelContent = "<?php

class {$ucModuleName}_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
}
";
file_put_contents($modelPath, $modelContent);

// Create view
$viewPath = "applications/rumahsakit/views/{$moduleName}/index.php";
if (!is_dir(dirname($viewPath))) {
    mkdir(dirname($viewPath), 0755, true);
}
$viewContent = "<h1>{$ucModuleName} Module</h1>";
file_put_contents($viewPath, $viewContent);

echo "Module '{$moduleName}' created successfully.\n";

