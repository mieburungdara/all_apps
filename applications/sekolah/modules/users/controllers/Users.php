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
            $this->session->set_flash('error', 'Invalid CSRF token. Please try submitting the form again.');
            return false;
        }
        return true;
    }

    public function login() {
        if ($this->_is_valid_post()) {
            $this->_handle_login();
        }
        $data['title'] = 'Login';
        $this->load->view('users/login', $data, 'auth');
    }

    private function _handle_login() {
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

    public function register() {
        if ($this->_is_valid_post()) {
            $this->_handle_register();
        }
        $data['title'] = 'Register';
        $this->load->view('users/register', $data, 'auth');
    }

    private function _handle_register() {
        $this->Users_model->register_user($this->input->post());
        $this->session->set_flash('success', 'Registration successful! Please login.');
        $this->response->redirect('/sekolah/users/login');
    }

    public function logout() {
        $this->session->destroy();
        $this->response->redirect('/sekolah/users/login');
    }

    public function profile() {
        $this->_auth_check();
        $user_id = $this->session->get('user_id');

        if ($this->_is_valid_post()) {
            $this->_handle_profile_update($user_id);
            $this->response->redirect('/sekolah/users/profile');
            return;
        }

        $data['title'] = 'My Profile';
        $data['user'] = $this->Users_model->get_user_by_id($user_id);
        $this->load->view('users/profile', $data);
    }

    private function _handle_profile_update($user_id) {
        $action = $this->input->post('action');

        if ($action === 'update_profile') {
            $rules = [
                'nama' => 'required|alpha_space',
                'email' => 'required|email|is_unique[users.email.id.' . $user_id . ']',
            ];
    
            if (!empty($this->input->post('password'))) {
                $rules['password'] = 'min_length[8]';
            }

            if (!$this->input->validate($rules)) {
                $this->session->set_flash('errors', $this->input->get_errors());
                $this->session->set_old_input($this->input->post());
            } else {
                $user_data = [
                    'nama' => $this->input->post('nama'),
                    'email' => $this->input->post('email'),
                    'password' => $this->input->post('password'),
                ];
                $this->Users_model->update_user($user_id, $user_data);
                $this->session->set_flash('success', 'Profile updated successfully!');
            }

        } elseif ($action === 'regenerate_key') {
            $this->Users_model->regenerate_api_key($user_id);
            $this->session->set_flash('success', 'API Key regenerated successfully!');
        }
    }

    public function my_children() {
        $this->_authorize('student.view_own_child_data');
        $parent_id = $this->session->get('user_id');
        $this->load->model('Student_Parent_model');

        $data['title'] = 'My Children';
        $data['children'] = $this->Student_Parent_model->get_children_for_parent($parent_id);
        $this->load->view('users/my_children', $data);
    }

    public function view_child($child_id) {
        $this->_authorize('student.view_own_child_data');
        $parent_id = $this->session->get('user_id');
        $this->load->model('Student_Parent_model');

        // Security check: ensure the requested child belongs to the logged-in parent
        if (!$this->Student_Parent_model->is_child_of_parent($child_id, $parent_id)) {
            $this->response->redirect('/sekolah/errors/show_403');
            return;
        }

        $this->load->model('Attendance_model');
        $data['title'] = 'Child Report';
        $data['child'] = $this->Users_model->get_user_by_id($child_id);
        $data['attendance_history'] = $this->Attendance_model->get_user_attendance_history($child_id, 30); // Get last 30 records

        $this->load->view('users/view_child_report', $data);
    }

    public function dashboard() {
        $this->_auth_check();
        $data['title'] = 'User Dashboard';
        $this->load->view('users/dashboard', $data);
    }

    public function telegram_link() {
        $this->_auth_check();
        $user_id = $this->session->get('user_id');
        $this->load->model('User_telegram_model');

        $user_telegram = $this->User_telegram_model->get_user_telegram_by_user_id($user_id);

        if (!$user_telegram || !$user_telegram['is_verified']) {
            // Generate a new token if not linked or not verified
            $token = bin2hex(random_bytes(16)); // 32-character hex token
            if ($user_telegram) {
                // Update existing unverified entry
                $this->db->update('user_telegram', ['verification_token' => $token, 'is_verified' => FALSE], [['user_id', '=', $user_id]]);
            } else {
                // Create new entry
                $this->User_telegram_model->create_user_telegram_entry($user_id, $token);
            }
            $data['verification_token'] = $token;
        } else {
            $data['telegram_chat_id'] = $user_telegram['telegram_chat_id'];
        }

        $data['title'] = 'Link Telegram Account';
        $data['user_telegram'] = $user_telegram;
        $this->load->view('users/telegram_link', $data);
    }
}
