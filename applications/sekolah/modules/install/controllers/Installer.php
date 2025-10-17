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
            $user_id = $this->Users_model->register_user($post_data);

            // Create default roles
            $superadmin_role_id = $this->Auth_model->create_role('superadmin');
            $admin_role_id = $this->Auth_model->create_role('admin');
            $user_role_id = $this->Auth_model->create_role('user');
            $parent_role_id = $this->Auth_model->create_role('Wali Murid');

            // Create default permissions
            $perm_manage_users = $this->Auth_model->create_permission('users.manage', 'Manage all users');
            $perm_manage_attendance = $this->Auth_model->create_permission('attendance.manage', 'Manage all attendance records');
            $perm_view_own_attendance = $this->Auth_model->create_permission('attendance.view_own', 'View own attendance');
            $perm_manage_roles = $this->Auth_model->create_permission('roles.manage', 'Manage user roles and permissions');
            $perm_perform_attendance = $this->Auth_model->create_permission('attendance.perform', 'Can perform check-in and check-out');
            $perm_view_child_data = $this->Auth_model->create_permission('student.view_own_child_data', 'View own child data');

            // Assign permissions to roles
            // Admin can manage users and attendance
            $this->Auth_model->assign_permission_to_role($perm_manage_users, $admin_role_id);
            $this->Auth_model->assign_permission_to_role($perm_manage_attendance, $admin_role_id);
            $this->Auth_model->assign_permission_to_role($perm_perform_attendance, $admin_role_id); // Admin also needs to check-in

            // User (student) can only view their own attendance and perform it
            $this->Auth_model->assign_permission_to_role($perm_view_own_attendance, $user_role_id);
            $this->Auth_model->assign_permission_to_role($perm_perform_attendance, $user_role_id);

            // Parent can view child data
            $this->Auth_model->assign_permission_to_role($perm_view_child_data, $parent_role_id);

            // Assign superadmin role to the first user
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
