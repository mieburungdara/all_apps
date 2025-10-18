<?php

class Telegram_webhook extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        // No authentication needed for webhook
        $this->load->library('Telegram_bot');
        $this->load->model('User_telegram_model');
        $this->load->model('Users_model');
    }

    public function index() {
        $update = json_decode(file_get_contents('php://input'), true);

        if (isset($update['message'])) {
            $message = $update['message'];
            $chat_id = $message['chat']['id'];
            $text = $message['text'] ?? '';

            if (strpos($text, '/start ') === 0) {
                $token = trim(substr($text, 7)); // Extract token after /start
                $user_telegram = $this->User_telegram_model->get_user_telegram_by_token($token);

                if ($user_telegram) {
                    $this->User_telegram_model->update_telegram_chat_id($user_telegram['user_id'], $chat_id);
                    $this->telegram_bot->sendMessage($chat_id, "Akun Telegram Anda berhasil ditautkan!");
                } else {
                    $this->telegram_bot->sendMessage($chat_id, "Token verifikasi tidak valid atau sudah kadaluarsa.");
                }
            } else {
                $this->telegram_bot->sendMessage($chat_id, "Halo! Kirimkan token verifikasi Anda dengan format /start [token] untuk menautkan akun Anda.");
            }
        }

        // Always return a 200 OK status to Telegram
        http_response_code(200);
    }
}
