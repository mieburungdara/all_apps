<?php

class Roles extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->_authorize('roles.manage');
    }

    public function index() {
        $this->load->model('Auth_model');
        $data['title'] = 'Role Management';
        $data['roles'] = $this->Auth_model->get_all_roles();
        $this->load->view('admin/roles/index', $data);
    }

    public function edit($role_id) {
        $this->load->model('Auth_model');
        $data['title'] = 'Edit Role';
        $data['role'] = $this->Auth_model->get_role_by_id($role_id);
        $data['permissions'] = $this->Auth_model->get_all_permissions();
        $data['role_permissions'] = $this->Auth_model->get_permissions_for_role($role_id);
        $this->load->view('admin/roles/form', $data);
    }

    public function update() {
        $this->load->model('Auth_model');
        $role_id = $this->input->post('role_id');
        $permission_ids = $this->input->post('permissions') ?? [];
        $this->Auth_model->update_role_permissions($role_id, $permission_ids);
        $this->session->set_flash('success', 'Role permissions updated successfully!');
        $this->response->redirect('/sekolah/roles');
    }
}
