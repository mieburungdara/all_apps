<?php

class Admin extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->_authorize(['admin']);
    }

    public function index() {
        $data['title'] = 'Admin Dashboard';
        $this->load->view('admin/dashboard', $data);
    }

    public function users() {
        $this->load->model('Users_model');
        $this->load->model('Auth_model');
        $users = $this->Users_model->get_all_users();
        foreach ($users as &$user) {
            $user['roles'] = $this->Auth_model->get_user_roles($user['id']);
        }

        $data['title'] = 'User Management';
        $data['users'] = $users;
        $this->load->view('admin/users/index', $data);
    }

    public function add_user() {
        $this->load->model('Auth_model');
        $data['title'] = 'Add User';
        $data['all_roles'] = $this->Auth_model->get_all_roles();
        $this->load->view('admin/users/form', $data);
    }

    public function edit_user($id) {
        $this->load->model('Users_model');
        $this->load->model('Auth_model');
        $data['title'] = 'Edit User';
        $data['user'] = $this->Users_model->get_user_by_id($id);
        $data['user_roles'] = $this->Auth_model->get_user_roles($id);
        $data['all_roles'] = $this->Auth_model->get_all_roles();
        $this->load->view('admin/users/form', $data);
    }

    public function save_user() {
        $this->load->model('Users_model');
        $this->load->model('Auth_model');

        $user_id = $this->input->post('id');
        $user_data = [
            'nama' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
        ];
        $role_ids = $this->input->post('roles') ?? [];

        if (empty($user_id)) { // New user
            unset($user_data['password']); // Let register_user handle hashing
            $new_user_data = [
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
            ];
            $user_id = $this->Users_model->register_user($new_user_data);
        } else { // Existing user
            $this->Users_model->update_user($user_id, $user_data);
        }

        $this->Auth_model->update_user_roles($user_id, $role_ids);
        $this->response->redirect('/sekolah/admin/users');
    }

    public function delete_user($id) {
        $this->load->model('Users_model');
        $this->Users_model->delete_user($id);
        $this->response->redirect('/sekolah/admin/users');
    }
}
