<?php
class ControllerFePagesTelegram extends Controller {

    public function index() {
        $this->load->model('setting/setting');
        $util_telegram_chat_ids = 'fe_telegram_chat_ids';

        if (isset($this->request->post['telegram_chat_ids'])) {
            $this->model_setting_setting->editSetting('fe_telegram', ['fe_telegram_chat_ids' => $this->request->post['telegram_chat_ids'] ]);
        }

        $data['setting_telegram'] = $this->model_setting_setting->getSetting('fe_telegram');

        $data['lang_heading_title'] = 'Настройки Телеграм';

		$this->document->setTitle($data['lang_heading_title']);

		$data['user_token'] = $this->session->data['user_token'];
        $data['action'] = $this->url->link('fe/pages/telegram', "user_token=${data['user_token']}", true);

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('pages/banner');

        $this->response->setOutput($this->load->view('fe/pages/telegram', $data));
    }

    public function getToken() {
        $this->config->load('fe_config');
        $telegram_token = $this->config->get('fe_telegram_token');

        $this->load->model('setting/setting');
        $setting_telegram = $this->model_setting_setting->getSetting('fe_telegram');

        $output = array_merge($setting_telegram, ['telegram_token' => $telegram_token]);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($output));
    }

}
