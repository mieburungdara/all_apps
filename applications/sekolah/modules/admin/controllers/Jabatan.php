<?php

class Jabatan extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->_authorize('jabatan.manage');
    }

    public function index() {
        $this->load->model('Auth_model');
        $data['title'] = 'Jabatan Management';
        $data['jabatan'] = $this->Auth_model->get_all_jabatan();
        $this->load->view('admin/jabatan/index', $data);
    }

    public function edit($jabatan_id) {
        $this->load->model('Auth_model');
        $data['title'] = 'Edit Jabatan';
        $data['jabatan'] = $this->Auth_model->get_jabatan_by_id($jabatan_id);
        $data['permissions'] = $this->Auth_model->get_all_permissions();
        $data['jabatan_permissions'] = $this->Auth_model->get_permissions_for_jabatan($jabatan_id);
        $this->load->view('admin/jabatan/form', $data);
    }

    public function update() {
        $this->load->model('Auth_model');
        $jabatan_id = $this->input->post('jabatan_id');
        $permission_ids = $this->input->post('permissions') ?? [];
        $this->Auth_model->update_jabatan_permissions($jabatan_id, $permission_ids);
        $this->session->set_flash('success', 'Jabatan permissions updated successfully!');
        $this->response->redirect('/sekolah/jabatan');
    }
}
