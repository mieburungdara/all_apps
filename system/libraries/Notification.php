<?php

class Notification {
    public function send($recipient, $message) {
        // In a real application, this would integrate with an SMS/WhatsApp/Push notification service.
        // For now, we'll just log the notification.
        log_message('info', 'Sending notification to ' . $recipient . ': ' . $message);
        return true;
    }
}
