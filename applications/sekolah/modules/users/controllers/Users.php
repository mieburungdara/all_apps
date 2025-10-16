<?php

class Users extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->load->model('Users_model', $this);
    }

    public function register() {
        $data = [];
        if ($this->input->method() == 'POST') {
            if (!$this->session->validate_csrf_token($this->input->csrf_token())) {
                show_error('Invalid CSRF token. Please try submitting the form again.');
            }

            $rules = [
                'nama' => 'required|alpha_space|min_length[3]',
                'email' => 'required|email|unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]'
            ];

            if ($this->input->validate($rules)) {
                $post_data = [
                    'nama' => $this->input->post('nama'),
                    'email' => $this->input->post('email'),
                    'password' => $this->input->post('password')
                ];

                if ($this->Users_model->register_user($post_data)) {
                    $this->session->set_flash('success', 'Registrasi berhasil! Silakan login.');
                    header('Location: /sekolah/users/login');
                    exit();
                } else {
                    $this->session->set_flash('error', 'Registrasi gagal. Email mungkin sudah digunakan.');
                }
            } else {
                // Pass errors to the view
                $data['errors'] = $this->input->get_errors();
                $this->session->flash_input($this->input->post());
            }
        }
        
        $data['__module_path'] = $this->module_path;
        $this->load->view('users/register', $data);
    }

    public function login() {
        if ($this->input->method() == 'POST') {
            if (!$this->session->validate_csrf_token($this->input->csrf_token())) {
                show_error('Invalid CSRF token. Please try submitting the form again.');
            }

            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->Users_model->check_login($email, $password);

            if ($user) {
                $this->session->set('user_id', $user['id']);
                $this->session->set('user_nama', $user['nama']);
                $this->session->set_flash('success', 'Selamat datang kembali, ' . $user['nama'] . '!');

                header('Location: /sekolah/dashboard');
                exit();
            } else {
                $this->session->set_flash('error', 'Login gagal! Email atau password salah.');
                header('Location: /sekolah/users/login');
                exit();
            }
        } else {
            $data['__module_path'] = $this->module_path;
            $this->load->view('users/login', $data);
        }
    }

    public function logout() {
        $this->session->destroy();
        $this->session = Session::getInstance(); 
        $this->session->set_flash('info', 'Anda telah berhasil logout.');
        header('Location: /sekolah/users/login');
        exit();
    }
}
