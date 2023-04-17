<?php

class ControllerExtensionModuleFelaximo extends Controller {

    protected $setting_code = 'fe_laximo';

    public function index() {
        $setting_code = $this->setting_code;
        $user_token = $this->session->data['user_token'];

        $this->load->model('setting/setting');

        $data['url_oem'] = $this->request->post['url_oem'] ?? null;
        $data['url_am'] = $this->request->post['url_am'] ?? null;
        $data['login'] = $this->request->post['login'] ?? null;
        $data['password'] = $this->request->post['password'] ?? null;

        if ($data['url_oem'] && $data['url_am'] && $data['login'] && $data['password']) {
            $this->model_setting_setting->editSetting($setting_code, [
                "${setting_code}_url_oem" => $data['url_oem'],
                "${setting_code}_url_am" => $data['url_am'],
                "${setting_code}_login" => $data['login'],
                "${setting_code}_password" => $data['password']
            ]);
        }

        $laximo_setting = $this->model_setting_setting->getSetting($setting_code);
        $data['url_oem'] = $laximo_setting["${setting_code}_url_oem"] ?? '';
        $data['url_am'] = $laximo_setting["${setting_code}_url_am"] ?? '';
        $data['login'] = $laximo_setting["${setting_code}_login"] ?? '';
        $data['password'] = $laximo_setting["${setting_code}_password"] ?? '';

        $data['heading_title'] = 'FE Laximo';
        $data['form_action'] = $this->url->link('extension/module/felaximo', '', true) . "&user_token={$user_token}";

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/felaximo', $data));
    }

    public function install() {
        $setting_code = $this->setting_code;
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting($setting_code, [
            "${setting_code}_url_oem" => 'https://ws.laximo.net/wsdl/Laximo.OEM_Login.xml',
            "${setting_code}_url_am" => 'https://ws.laximo.net/wsdl/Laximo.Aftermarket_Login.xml',
            "${setting_code}_login" => '',
            "${setting_code}_password" => ''
        ]);
    }

    public function uninstall() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting($this->setting_code);
    }

}
