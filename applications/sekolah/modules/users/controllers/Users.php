<?php

class Users extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->load->model('Users_model', $this);
    }

    public function register() {
        if ($this->input->method() == 'POST') {
            $data = [
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password')
            ];

            if ($this->Users_model->register_user($data)) {
                header('Location: /sekolah/users/login');
                exit();
            } else {
                echo "Registration failed!";
            }
        } else {
            $data['__module_path'] = $this->module_path;
            $this->load->view('users/register', $data);
        }
    }

    public function login() {
        if ($this->input->method() == 'POST') {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->Users_model->check_login($email, $password);

            if ($user) {
                $this->session->set('user_id', $user['id']);
                $this->session->set('user_nama', $user['nama']);

                header('Location: /sekolah/dashboard');
                exit();
            } else {
                // TODO: Show error message on the login page
                echo "Login failed! Invalid email or password.";
            }
        } else {
            $data['__module_path'] = $this->module_path;
            $this->load->view('users/login', $data);
        }
    }

    public function logout() {
        $this->session->destroy();
        header('Location: /sekolah/users/login');
        exit();
    }
}