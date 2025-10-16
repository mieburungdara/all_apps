<?php

class Users extends Controller {

    public function __construct($module_path) {
        parent::__construct($module_path);
        $this->load_model('Users_model');
    }

    // Display the registration page
    public function register() {
        // If form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nama' => $_POST['nama'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];

            if ($this->Users_model->register_user($data)) {
                // Redirect to login page on success
                header('Location: /sekolah/users/login');
                exit();
            } else {
                // Handle registration failure
                echo "Registration failed!";
            }
        } else {
            // Show the registration form
            $this->load_view('register');
        }
    }

    // Display the login page
    public function login() {
        // If form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->Users_model->check_login($email, $password);

            if ($user) {
                // Handle successful login (we need sessions for this!)
                echo "Login successful! Welcome, " . $user['nama'];
                // For now, just show a success message.
                // Later, we will start a session here.
            } else {
                // Handle login failure
                echo "Login failed! Invalid email or password.";
            }
        } else {
            // Show the login form
            $this->load_view('login');
        }
    }
}
