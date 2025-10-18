<?php

class User_telegram_model extends Model {

    public function get_user_telegram_by_user_id($user_id) {
        return $this->db->get('user_telegram', [['user_id', '=', $user_id]], true);
    }

    public function get_user_telegram_by_token($token) {
        return $this->db->get('user_telegram', [['verification_token', '=', $token]], true);
    }

    public function create_user_telegram_entry($user_id, $token) {
        $data = [
            'user_id' => $user_id,
            'verification_token' => $token,
        ];
        return $this->db->insert('user_telegram', $data);
    }

    public function update_telegram_chat_id($user_id, $chat_id) {
        $data = [
            'telegram_chat_id' => $chat_id,
            'is_verified' => TRUE,
            'verification_token' => null, // Clear token after verification
        ];
        return $this->db->update('user_telegram', $data, [['user_id', '=', $user_id]]);
    }

    public function delete_user_telegram_entry($user_id) {
        return $this->db->delete('user_telegram', [['user_id', '=', $user_id]]);
    }
}
