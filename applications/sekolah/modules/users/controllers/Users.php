<?php

class Users extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->load_model('Users_model');
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
            $this->load_view('register');
        }
    }

    public function login() {
        if ($this->input->method() == 'POST') {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->Users_model->check_login($email, $password);

            if ($user) {
                // Set user data in session
                $this->session->set('user_id', $user['id']);
                $this->session->set('user_nama', $user['nama']);

                // Redirect to the main dashboard
                header('Location: /sekolah/dashboard');
                exit();
            } else {
                // TODO: Show error message on the login page
                echo "Login failed! Invalid email or password.";
            }
        } else {
            $this->load_view('login');
        }
    }

    public function logout() {
        $this->session->destroy();
        header('Location: /sekolah/users/login');
        exit();
    }
}
