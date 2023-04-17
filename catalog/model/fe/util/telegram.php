<?php
class ModelFeUtilTelegram extends Model {

    private $telegram_token = '';

    public function __construct($registry) {
        parent::__construct($registry);
        $this->config->load('fe_config');
        $this->telegram_token = $this->config->get('fe_telegram_token');
    }

    public function sendNotifications($text) {
        // $telegram_ids = [
        //     // '962851151', // Dmitri
        //     '1911928018' // My
        // ];

        $this->load->model('setting/setting');
        $telegram_ids = preg_split('/[\s]+/', $this->model_setting_setting->getSetting('fe_telegram')['fe_telegram_chat_ids'] ?? '');

        foreach ($telegram_ids as $id) {
            $ch = curl_init();
            curl_setopt_array(
                $ch,
                array(
                    CURLOPT_URL => 'https://api.telegram.org/bot' . $this->telegram_token . '/sendMessage',
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_POSTFIELDS => array(
                        'chat_id' => $id,
                        'text' => $text,
                    ),
                )
            );
            curl_exec($ch);
        }
    }

}
