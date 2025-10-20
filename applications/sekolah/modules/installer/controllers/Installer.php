<?php

class Installer extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
    }

    public function index() {
        $step = $this->input->get('step', 1);

        switch ($step) {
            case 2:
                $this->_system_check_step();
                break;
            case 3:
                $this->_database_setup_step();
                break;
            case 4:
                $this->_app_config_step();
                break;
            case 5:
                $this->_create_admin_step();
                break;
            case 6:
                $this->_install_progress_step();
                break;
            default:
                $this->_welcome_step();
                break;
        }
    }

    private function _welcome_step() {
        $data['title'] = 'Welcome to Installer';
        $this->load->view('welcome', $data, 'auth');
    }

    private function _system_check_step() {
        $data['title'] = 'System Requirements';

        $checks = [];
        // Check PHP Version
        $php_version_required = '8.0';
        $php_version_ok = version_compare(PHP_VERSION, $php_version_required, '>=');
        $checks[] = [
            'name' => 'PHP Version >=' . $php_version_required,
            'status' => $php_version_ok,
            'message' => $php_version_ok ? ('Your version: ' . PHP_VERSION) : 'PHP version is too old.'
        ];

        // Check PDO MySQL Driver
        $pdo_ok = extension_loaded('pdo_mysql');
        $checks[] = [
            'name' => 'PDO MySQL Driver',
            'status' => $pdo_ok,
            'message' => $pdo_ok ? 'Installed' : 'Not installed or enabled.'
        ];

        // Check Config Folder Writable
        $config_path = APPPATH . 'config/';
        $config_writable = is_writable($config_path);
        $checks[] = [
            'name' => 'Config folder is writable', // (' . realpath($config_path) . ')',
            'status' => $config_writable,
            'message' => $config_writable ? 'Writable' : 'Not writable. Please check permissions.'
        ];

        // Check Logs Folder Writable
        $logs_path = FCPATH . 'logs/';
        $logs_writable = is_writable($logs_path);
        $checks[] = [
            'name' => 'Logs folder is writable', // (' . realpath($logs_path) . ')',
            'status' => $logs_writable,
            'message' => $logs_writable ? 'Writable' : 'Not writable. Please check permissions.'
        ];

        $data['checks'] = $checks;
        $data['all_ok'] = !in_array(false, array_column($checks, 'status'));

        $this->load->view('system_check', $data, 'auth');
    }

    private function _app_config_step() {
        $data['title'] = 'Application Configuration';

        // Redirect if database is not configured yet
        if (!file_exists(APPPATH . 'config/database.php')) {
            $this->response->redirect('/sekolah/installer?step=3');
            return;
        }

        if ($this->input->method() === 'POST') {
            $app_name = $this->input->post('app_name');
            $base_url = $this->input->post('base_url');
            $language = $this->input->post('language');
            $timezone = $this->input->post('timezone');

            $app_config_content = "<?php\n\nreturn [\n    'app_name' => '{$app_name}',\n    'base_url' => '{$base_url}',\n    'language' => '{$language}',\n    'timezone' => '{$timezone}',\n];\n";
            file_put_contents(APPPATH . 'config/app.php', $app_config_content);

            // Proceed to the next step
            $this->response->redirect('/sekolah/installer?step=5');
            return;
        }

        $this->load->view('app_config', $data, 'auth');
    }

    private function _create_admin_step() {
        $data['title'] = 'Create Admin Account';

        // Redirect if previous steps are not completed
        if (!file_exists(APPPATH . 'config/database.php') || !file_exists(APPPATH . 'config/app.php')) {
            $this->response->redirect('/sekolah/installer');
            return;
        }

        // Load models needed for this step (handled in POST)


        if ($this->input->method() === 'POST') {
            try {
                // Run migrations FIRST to ensure all tables exist
                $migration_output = shell_exec("php " . FCPATH . "migrate.php sekolah");

                // Manually connect to the database
                $db_config = require APPPATH . 'config/database.php';

                // Force TCP/IP connection for localhost
                $db_host = ($db_config['host'] === 'localhost') ? '127.0.0.1' : $db_config['host'];

                $dsn = "mysql:host={$db_host};port={$db_config['port']};dbname={$db_config['database']};charset=utf8";
                $pdo = new PDO($dsn, $db_config['username'], $db_config['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Ensure the selected role exists before assigning it
                $role_id = $this->input->post('role_id');
                $role_name = '';
                switch ($role_id) {
                    case 1: $role_name = 'superadmin'; break;
                    case 2: $role_name = 'admin'; break;
                    case 3: $role_name = 'user'; break;
                }

                if ($role_name) {
                    $stmt = $pdo->prepare("INSERT INTO jabatan (id, nama_jabatan) VALUES (?, ?) ON DUPLICATE KEY UPDATE nama_jabatan=nama_jabatan");
                    $stmt->execute([$role_id, $role_name]);
                }

                // Create the admin user
                $nama = $this->input->post('nama');
                $email = $this->input->post('email');
                $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                $api_key = bin2hex(random_bytes(32));

                $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, api_key) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nama, $email, $password, $api_key]);
                $user_id = $pdo->lastInsertId();

                // Assign the selected role
                $role_id = $this->input->post('role_id');
                $stmt_role = $pdo->prepare("INSERT INTO user_jabatan (user_id, jabatan_id) VALUES (?, ?)");
                $stmt_role->execute([$user_id, $role_id]);

                // Create installed.lock file
                file_put_contents(APPPATH . 'installed.lock', time());

                // Add debug info to the success message
                $debug_info = "[Debug Info: Created User ID: {$user_id}, Assigned Role ID: {$role_id}, Role Rows Affected: {$stmt_role->rowCount()}]";
                $this->session->set_flash('success', 'Installation complete! ' . $debug_info . ' You can now log in.');
                $this->session->set_flash('migration_output', $migration_output);
                $this->response->redirect('/sekolah/users/login');

            } catch (Exception $e) {
                echo "<pre>INSTALLATION FAILED:\n";
                print_r($e);
                die();
            }
            return;
        }

        // Manually define roles for the first admin user, as the DB isn't fully seeded yet.
        $data['roles'] = [
            ['id' => 1, 'nama_jabatan' => 'superadmin'],
            ['id' => 2, 'nama_jabatan' => 'admin'],
            ['id' => 3, 'nama_jabatan' => 'user'],
        ];

        $this->load->view('create_admin', $data, 'auth');
    }

    private function _install_progress_step() {
        $data['title'] = 'Installation Progress';
        $this->load->view('install_progress', $data, 'auth');
    }

    public function run_installation() {
        // Ensure this can't be run if already installed
        if (file_exists(APPPATH . 'installed.lock')) {
            return;
        }

        // Use this to stream progress to the client
        $send_progress = function($progress, $message, $status = 'progress', $redirect_url = null) {
            $payload = [
                'progress' => $progress, 
                'message' => $message, 
                'status' => $status
            ];
            if ($redirect_url) {
                $payload['redirect_url'] = $redirect_url;
            }
            echo json_encode($payload);
            echo "--CHUNK--"; // Delimiter
            ob_flush();
            flush();
            sleep(1); // Simulate work
        };

        // Load models
        $this->load->model('Users_model');
        $this->load->model('Auth_model');

        // 1. Run Migrations
        $send_progress(25, "Running database migrations...");
        ob_start();
        require_once FCPATH . 'migrate.php';
        $migration_output = ob_get_clean();
        $send_progress(50, "Database migrations complete.");

        // 2. Create Admin User
        $send_progress(75, "Creating admin account...");
        $admin_data = $this->session->get('install_admin_data');
        if ($admin_data) {
            $user_id = $this->Users_model->register_user([
                'nama' => $admin_data['nama'],
                'email' => $admin_data['email'],
                'password' => $admin_data['password'],
            ]);
            $this->Auth_model->assign_jabatan($user_id, $admin_data['role_id']);
            $send_progress(90, "Admin account created successfully.");
        } else {
            $send_progress(90, "Could not find admin data. Skipping admin creation.");
        }

        // 3. Finalize Installation
        $send_progress(95, "Finalizing installation...");
        file_put_contents(APPPATH . 'installed.lock', time());

        $this->session->set_flash('success', 'Installation complete! You can now log in.');
        $send_progress(100, "All tasks complete!", 'complete', '/sekolah/users/login');
        exit; // End the script
    }

    private function _database_setup_step() {
        $data['title'] = 'Database Setup (Step 3/3)';

        if ($this->input->method() === 'POST') {
            $db_host = $this->input->post('db_host');
            $db_port = $this->input->post('db_port', 3306);
            $db_name = $this->input->post('db_name');
            $db_user = $this->input->post('db_user');
            $db_pass = $this->input->post('db_pass');
            $create_db = $this->input->post('create_db');

            try {
                $dsn = "mysql:host={$db_host};port={$db_port}";
                $pdo = new PDO($dsn, $db_user, $db_pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($create_db) {
                    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}`");
                }

                // Reconnect with the database selected
                $dsn .= ";dbname={$db_name}";
                $pdo = new PDO($dsn, $db_user, $db_pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Save database credentials to config file
                $config_content = "<?php\n\nreturn [\n    'driver'   => 'mysql',\n    'host'     => '{$db_host}',\n    'port'     => '{$db_port}',\n    'database' => '{$db_name}',\n    'username' => '{$db_user}',\n    'password' => '{$db_pass}',\n];\n";
                file_put_contents(APPPATH . 'config/database.php', $config_content);

                // For now, just save the config and move to the next step
                $this->response->redirect('/sekolah/installer?step=4');

            } catch (PDOException $e) {
                $this->session->set_flash('error', 'An error occurred: ' . $e->getMessage());
                $this->response->redirect('/sekolah/installer?step=3');
            }
            return;
        }

        // Pre-fill credentials if config file exists
        $data['db_config'] = [];
        if (file_exists(APPPATH . 'config/database.php')) {
            $data['db_config'] = require APPPATH . 'config/database.php';
        }

        $this->load->view('index', $data, 'auth');
    }

    public function check_db_connection() {
        $this->response->set_header('Content-Type', 'application/json');

        if ($this->input->method() !== 'POST') {
            $this->response->json(['success' => false, 'message' => 'Invalid request method.']);
            return;
        }

        $db_host = $this->input->post('db_host');
        $db_port = $this->input->post('db_port', 3306);
        $db_name = $this->input->post('db_name');
        $db_user = $this->input->post('db_user');
        $db_pass = $this->input->post('db_pass');

        try {
            // First, check connection to the server without the database
            $dsn = "mysql:host={$db_host};port={$db_port}";
            $pdo = new PDO($dsn, $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Then, check if the database exists
            $stmt = $pdo->query("SHOW DATABASES LIKE '{$db_name}'");
            if ($stmt->rowCount() > 0) {
                $this->response->json(['success' => true, 'message' => 'Connection successful. Database exists.']);
            } else {
                $this->response->json(['success' => false, 'message' => 'Connection successful, but database does not exist. You can choose to create it.']);
            }

        } catch (PDOException $e) {
            $this->response->json(['success' => false, 'message' => 'Server connection failed: ' . $e->getMessage()]);
        }
        return;
    }

}
