<?php

class Users_model extends Model {

    public function register_user($data) {
        // Hash the password for security
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Use the generic insert method from the base Model
        return $this->insert('users', $data);
    }

    public function check_login($email, $password) {
        // Use the generic get method to find the user
        $user = $this->get('users', ['email' => $email]);

        // Check if user exists and password is correct
        if (count($user) === 1 && password_verify($password, $user[0]['password'])) {
            return $user[0]; // Return user data
        } else {
            return false; // Login failed
        }
    }
}
