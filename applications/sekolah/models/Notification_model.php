<?php

class Notification_model extends Model {

    public function create_notification($recipient_id, $message) {
        $data = [
            'recipient_user_id' => $recipient_id,
            'message' => $message
        ];
        $this->db->insert('notifications', $data);

        // Attempt to send Telegram notification
        $this->send_telegram_notification($recipient_id, $message);

        return true;
    }

    public function send_telegram_notification($user_id, $message) {
        $this->load->model('User_telegram_model');
        $this->load->library('Telegram_bot');

        $user_telegram = $this->User_telegram_model->get_user_telegram_by_user_id($user_id);

        if ($user_telegram && $user_telegram['is_verified'] && $user_telegram['telegram_chat_id']) {
            return $this->telegram_bot->sendMessage($user_telegram['telegram_chat_id'], $message);
        }
        return false;
    }

    public function get_unread_notifications($user_id) {
        return $this->db->get('notifications', [
            ['recipient_user_id', '=', $user_id],
            ['is_read', '=', 0]
        ]);
    }

    public function mark_as_read($notification_id, $user_id) {
        // Ensure a user can only mark their own notifications as read
        return $this->db->update('notifications', ['is_read' => 1], [
            ['id', '=', $notification_id],
            ['recipient_user_id', '=', $user_id]
        ]);
    }
}
