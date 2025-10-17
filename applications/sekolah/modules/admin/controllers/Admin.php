<?php

class Admin extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->_authorize('users.manage');
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
        $this->load->model('Student_Parent_model');

        $data['title'] = 'Edit User';
        $data['user'] = $this->Users_model->get_user_by_id($id);
        $data['user_roles'] = $this->Auth_model->get_user_roles($id);
        $data['all_roles'] = $this->Auth_model->get_all_roles();
        
        // For parent-child relationship management
        $data['all_students'] = $this->Users_model->get_all_users(); // Simplified: In a real app, you'd filter for students
        $data['child_ids'] = $this->Student_Parent_model->get_child_ids_for_parent($id);

        $this->load->view('admin/users/form', $data);
    }

    public function save_user() {
        $user_id = $this->input->post('id');

        // Define validation rules
        $rules = [
            'nama' => 'required|alpha_space',
            'email' => 'required|email|is_unique[users.email.id.' . $user_id . ']',
        ];

        if (empty($user_id) || !empty($this->input->post('password'))) {
            $rules['password'] = 'required|min_length[8]';
        }

        if (!$this->input->validate($rules)) {
            $this->session->set_flash('errors', $this->input->get_errors());
            $this->session->set_old_input($this->input->post());

            if ($user_id) {
                $this->response->redirect('/sekolah/admin/edit_user/' . $user_id);
            } else {
                $this->response->redirect('/sekolah/admin/add_user');
            }
            return; // Stop execution
        }

        $this->load->model('Users_model');
        $this->load->model('Auth_model');
        $this->load->model('Student_Parent_model');

        $user_data = [
            'nama' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
        ];
        $role_ids = $this->input->post('roles') ?? [];
        $child_ids = $this->input->post('children') ?? [];

        if (empty($user_id)) { // New user
            $user_id = $this->Users_model->register_user($user_data);
        } else { // Existing user
            $this->Users_model->update_user($user_id, $user_data);
        }

        $this->Auth_model->update_user_roles($user_id, $role_ids);
        $this->Student_Parent_model->update_children_for_parent($user_id, $child_ids);

        $this->session->set_flash('success', 'User saved successfully!');
        $this->response->redirect('/sekolah/admin/users');
    }

    public function delete_user($id) {
        $this->load->model('Users_model');
        $this->Users_model->delete_user($id);
        $this->response->redirect('/sekolah/admin/users');
    }
}
