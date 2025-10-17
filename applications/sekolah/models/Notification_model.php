<?php

class Notification_model extends Model {

    public function create_notification($recipient_id, $message) {
        $data = [
            'recipient_user_id' => $recipient_id,
            'message' => $message
        ];
        return $this->db->insert('notifications', $data);
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
