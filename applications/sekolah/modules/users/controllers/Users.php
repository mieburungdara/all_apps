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
                $this->session->set_flash('success', 'Registrasi berhasil! Silakan login.');
                header('Location: /sekolah/users/login');
                exit();
            } else {
                $this->session->set_flash('error', 'Registrasi gagal. Email mungkin sudah digunakan.');
                header('Location: /sekolah/users/register');
                exit();
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
        // We need to start a new session to store the flash message
        $this->session = Session::getInstance(); 
        $this->session->set_flash('info', 'Anda telah berhasil logout.');
        header('Location: /sekolah/users/login');
        exit();
    }
}
