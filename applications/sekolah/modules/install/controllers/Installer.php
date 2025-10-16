<?php

class Installer extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        // We don't load any models by default
    }

    public function index() {
        $data['title'] = 'Installer';
        $data['php_version'] = phpversion();
        $data['pdo_enabled'] = extension_loaded('pdo_sqlite');
        $this->load->view('install/welcome', $data, 'auth');
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
        $data['title'] = 'Database Setup';
        $this->load->view('install/database', $data, 'auth');
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
            // Note: register_user automatically assigns the 'user' role.
            $user_id = $this->Users_model->register_user($post_data);

            // Create default roles and assign superadmin
            $this->Auth_model->create_role('superadmin');
            $this->Auth_model->create_role('admin');
            $superadmin_role_id = $this->Auth_model->create_role('superadmin'); // Re-calling ensures we get the ID
            
            // We need to update the user's roles, not just add a new one.
            $this->Auth_model->update_user_roles($user_id, [$superadmin_role_id]);

            $this->response->redirect('/sekolah/installer/finish');
        }
        $data['title'] = 'Create Admin';
        $this->load->view('install/admin', $data, 'auth');
    }

    public function finish() {
        file_put_contents(APPPATH . 'installed.lock', 'installed');
        $data['title'] = 'Finished';
        $this->load->view('install/finish', $data, 'auth');
    }
}
