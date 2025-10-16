<?php

class Users extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->load->model('Users_model');
    }

    private function _is_valid_post() {
        if ($this->input->method() !== 'POST') {
            return false;
        }
        if (!$this->session->validate_csrf_token($this->input->csrf_token())) {
            show_error('Invalid CSRF token. Please try submitting the form again.');
        }
        return true;
    }

    public function login() {
        if ($this->input->method() === 'POST') {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->Users_model->get_user_by_email($email);

            if ($user && password_verify($password, $user['password'])) {
                $this->session->set('user_id', $user['id']);
                $this->session->regenerate_id();
                $this->response->redirect('/sekolah/users/dashboard');
            } else {
                $this->session->set_flash('error', 'Invalid email or password.');
            }
        }
        $data['title'] = 'Login';
        $this->load->view('users/login', $data, 'auth');
    }

    public function register() {
        if ($this->input->method() === 'POST') {
            $this->Users_model->register_user($this->input->post());
            $this->session->set_flash('success', 'Registration successful! Please login.');
            $this->response->redirect('/sekolah/users/login');
        }
        $data['title'] = 'Register';
        $this->load->view('users/register', $data, 'auth');
    }

    public function logout() {
        $this->session->destroy();
        $this->session = Session::getInstance(); 
        $this->session->set_flash('info', 'Anda telah berhasil logout.');
        $this->response->redirect('/sekolah/users/login');
    }

    public function dashboard() {
        $this->_auth_check();
        $data['title'] = 'User Dashboard';
        $this->load->view('users/dashboard', $data);
    }
        $this->session->destroy();
        $this->session = Session::getInstance(); 
        $this->session->set_flash('info', 'Anda telah berhasil logout.');
        $this->response->redirect('/sekolah/users/login');
    }
}
