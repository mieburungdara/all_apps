<?php

class Installer extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        // We don't load any models by default
    }

    public function index() {
        $data['php_version'] = phpversion();
        $data['pdo_enabled'] = extension_loaded('pdo_sqlite');
        $this->load->view('install/welcome', $data);
    }

    public function database() {
        if ($this->input->method() == 'POST') {
            $db_name = $this->input->post('db_name');
            $db_path = APPPATH . 'database/' . $db_name . '.sqlite';

            try {
                new PDO('sqlite:' . $db_path);
                $config_content = "<?php\n\nreturn [\n    'path' => '{$db_path}',\n];\n";
                file_put_contents(APPPATH . 'config/database.php', $config_content);
                $this->response->redirect('/sekolah/installer/run_migration');
            } catch (Exception $e) {
                $this->session->set_flash('error', 'Could not create database: ' . $e->getMessage());
            }
        }
        $this->load->view('install/database');
    }
    
    public function run_migration()
    {
        $this->load->model('Migration_model');
        $this->Migration_model->run_migrations();
        $this->response->redirect('/sekolah/installer/admin');
    }

    public function admin() {
        if ($this->input->method() == 'POST') {
            $this->load->model('Users_model');
            $this->load->model('Auth_model');

            // Create user
            $post_data = [
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password')
            ];
            $user_id = $this->Users_model->register_user($post_data);

            // Create admin role and assign it
            $role_id = $this->Auth_model->create_role('admin');
            $this->Auth_model->assign_role($user_id, $role_id);

            $this->response->redirect('/sekolah/installer/finish');
        }
        $this->load->view('install/admin');
    }

    public function finish() {
        file_put_contents(APPPATH . 'installed.lock', 'installed');
        $this->load->view('install/finish');
    }
}
