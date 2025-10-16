<?php

class Users_model extends Model {

    public function register_user($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->insert('users', $data);
        $user_id = $this->db->last_insert_id();

        // Assign default 'user' role
        $this->load->model('Auth_model');
        $role_id = $this->Auth_model->create_role('user');
        $this->Auth_model->assign_role($user_id, $role_id);

        return $user_id;
    }

    public function check_login($email, $password) {
        // Use the generic get method to find the user
        $user = $this->get('users', [['email', '=', $email]]);

        // Check if user exists and password is correct
        if (count($user) === 1 && password_verify($password, $user[0]['password'])) {
            return $user[0]; // Return user data
        } else {
            return false; // Login failed
        }
    }

    public function get_user_by_email($email) {
        return $this->get('users', [['email', '=', $email]], true);
    }

    public function get_user_by_id($id) {
        return $this->get('users', [['id', '=', $id]], true);
    }

    public function get_all_users() {
        return $this->get('users');
    }

    public function update_user($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return $this->update('users', $data, [['id', '=', $id]]);
    }

    public function delete_user($id) {
        return $this->delete('users', [['id', '=', $id]]);
    }
}
