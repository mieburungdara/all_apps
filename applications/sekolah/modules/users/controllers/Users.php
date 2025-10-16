<?php

class Users extends Controller {

    public function __construct($module_path) {
        parent::__construct($module_path);
        $this->load_model('Users_model');
    }

    // Display the registration page
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

    // Display the login page
    public function login() {
        if ($this->input->method() == 'POST') {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->Users_model->check_login($email, $password);

            if ($user) {
                // Handle successful login (we need sessions for this!)
                echo "Login successful! Welcome, " . $user['nama'];
                // For now, just show a success message.
                // Later, we will start a session here.
            } else {
                echo "Login failed! Invalid email or password.";
            }
        } else {
            $this->load_view('login');
        }
    }
}