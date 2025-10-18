<?php

class Telegram_bot {
    private $bot_token;
    private $api_url;

    public function __construct() {
        // Load Telegram bot token from config
        $config = Config::getInstance();
        $telegram_config = $config->load('telegram');
        $this->bot_token = $telegram_config['bot_token'] ?? null;
        $this->api_url = 'https://api.telegram.org/bot' . $this->bot_token . '/';

        if (empty($this->bot_token)) {
            log_message('error', 'Telegram bot token is not configured.');
        }
    }

    public function sendMessage($chat_id, $message) {
        if (empty($this->bot_token)) {
            log_message('error', 'Cannot send Telegram message: bot token is missing.');
            return false;
        }

        $url = $this->api_url . 'sendMessage';
        $data = [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'HTML',
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            log_message('error', 'Failed to send Telegram message to chat ID ' . $chat_id);
            return false;
        }

        $response = json_decode($result, true);
        if (!$response['ok']) {
            log_message('error', 'Telegram API error: ' . $response['description']);
            return false;
        }

        return true;
    }
}

