<?php

class Installer extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        // We don't load any models by default
    }

    public function index() {
        $data['title'] = 'Installer';
        $data['php_version'] = phpversion();
        $this->load->view('install/welcome', $data, 'auth');
    }

    public function database() {
        if ($this->input->method() == 'POST') {
            $host = $this->input->post('db_host');
            $port = $this->input->post('db_port');
            $db_name = $this->input->post('db_name');
            $username = $this->input->post('db_username');
            $password = $this->input->post('db_password');

            $config_content = "<?php\n\nreturn [\n    'driver'    => 'mysql',\n    'host'      => '{$host}',\n    'port'      => '{$port}',\n    'database'  => '{$db_name}',\n    'username'  => '{$username}',\n    'password'  => '{$password}',\n];\n";
            file_put_contents(APPPATH . 'config/database.php', $config_content);
            $this->response->redirect('/sekolah/installer/run_migration');
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

            // Create default jabatan
            $superadmin_jabatan_id = $this->Auth_model->create_jabatan('superadmin');
            $admin_jabatan_id = $this->Auth_model->create_jabatan('admin');
            $user_jabatan_id = $this->Auth_model->create_jabatan('user');
            $parent_jabatan_id = $this->Auth_model->create_jabatan('Wali Murid');

            // Create default permissions
            $perm_manage_users = $this->Auth_model->create_permission('users.manage', 'Manage all users');
            $perm_manage_attendance = $this->Auth_model->create_permission('attendance.manage', 'Manage all attendance records');
            $perm_view_own_attendance = $this->Auth_model->create_permission('attendance.view_own', 'View own attendance');
            $perm_manage_jabatan = $this->Auth_model->create_permission('jabatan.manage', 'Manage user jabatan and permissions');
            $perm_perform_attendance = $this->Auth_model->create_permission('attendance.perform', 'Can perform check-in and check-out');
            $perm_teacher_manage_attendance = $this->Auth_model->create_permission('attendance.teacher_manage', 'Teacher can manage student attendance');
            $perm_view_child_data = $this->Auth_model->create_permission('student.view_own_child_data', 'View own child data');

            // Assign permissions to jabatan
            // Admin can manage users and attendance
            $this->Auth_model->assign_permission_to_jabatan($perm_manage_users, $admin_jabatan_id);
            $this->Auth_model->assign_permission_to_jabatan($perm_manage_attendance, $admin_jabatan_id);
            $this->Auth_model->assign_permission_to_jabatan($perm_perform_attendance, $admin_jabatan_id); // Admin also needs to check-in
            $this->Auth_model->assign_permission_to_jabatan($perm_teacher_manage_attendance, $admin_jabatan_id); // Admin can also manage student attendance

            // User (student) can only view their own attendance and perform it
            $this->Auth_model->assign_permission_to_jabatan($perm_view_own_attendance, $user_jabatan_id);
            $this->Auth_model->assign_permission_to_jabatan($perm_perform_attendance, $user_jabatan_id);

            // Parent can view child data
            $this->Auth_model->assign_permission_to_jabatan($perm_view_child_data, $parent_jabatan_id);

            // Assign superadmin jabatan to the first user
            $this->Auth_model->update_user_jabatan($user_id, [$superadmin_jabatan_id]);

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
