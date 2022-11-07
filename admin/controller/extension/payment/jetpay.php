<?php

class ControllerExtensionPaymentJetpay extends Controller
{
    private $error = array();

    public function install()
    {
        $defaultSettings = array(
            'payment_jetpay_failedstatus' => 10,
            'payment_jetpay_successstatus' => 15,
            'payment_jetpay_pendingstatus' => 1,
            'payment_jetpay_title' => 'Payment via Jetpay',
            'payment_jetpay_description' => 'You will be redirected to Jetpay payment page. All data you enter in that page are secured',
            'payment_jetpay_testmode' => 'on',
            'payment_jetpay_language' => 'ru',
            'payment_jetpay_sort_order' => 1,
            'payment_jetpay_status' => 1
        );
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('payment_jetpay', $defaultSettings);
    }

    public function index()
    {
        $this->load->language('extension/payment/jetpay');
        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->server['REQUEST_METHOD'] === 'POST' && $this->validate()) {
            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('payment_jetpay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');

        $options = [
            'projectid', 'secretkey', 'title', 'description', 'additional_parameters',
            'testmode', 'language', 'currency', 'popupmode',
            'failedstatus', 'successstatus', 'pendingstatus',
            'sort_order', 'geo_zone_id',
            'status'
        ];

        foreach ($options as $option) {
            $formValue = 'entry_' . $option;
            $formHelp = 'entry_' . $option . '_help';

            $data[$formValue] = $this->language->get($formValue);
            $data[$formHelp] = $this->language->get($formHelp);

            $postValue = 'payment_jetpay_' . $option;

            if ($option === 'currency') continue;

            if (isset($this->request->post[$postValue])) {
                $data[$postValue] = $this->request->post[$postValue];
            } else {
                $data[$postValue] = $this->config->get($postValue);
            }
        }

        if (empty($this->session->data['currency'])) {
            $this->session->data['currency'] = 'USD';
        }

        $this->load->model('localisation/currency');
        $data['payment_jetpay_currency'] = 'N/A';
        foreach ($this->model_localisation_currency->getCurrencies() as $result) {
            if ($result['code'] !== $this->session->data['currency']) {
                continue;
            }
            $data['payment_jetpay_currency'] = $result['title'];
        }

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $data['entry_callback_url'] = $this->language->get('entry_callback_url');
        $data['entry_callback_url_help'] = $this->language->get('entry_callback_url_help');

        $url = new Url(HTTP_CATALOG, $this->config->get('config_secure') ? HTTP_CATALOG : HTTPS_CATALOG);
        $data['callback_url'] = $url->link('extension/payment/jetpay/callback', '', true);

        $data['languages'] = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'zh', 'name' => 'Chinese']
        ];

        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        foreach ($this->error as $key => $text) {
            $data['error_' . $key] = $text;
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/jetpay', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('extension/payment/jetpay', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);


        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $data['user_token'] = $this->session->data['user_token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/jetpay', $data));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/jetpay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['payment_jetpay_title'])) {
            $this->error['warning_title'] = $this->language->get('warning_title');
        }

        if (empty($this->request->post['payment_jetpay_pendingstatus'])) {
            $this->error['warning_pendingstatus'] = $this->language->get('warning_pendingstatus');
        }

        if (empty($this->request->post['payment_jetpay_successstatus'])) {
            $this->error['warning_successstatus'] = $this->language->get('warning_successstatus');
        }

        if (empty($this->request->post['payment_jetpay_failedstatus'])) {
            $this->error['warning_failedstatus'] = $this->language->get('warning_failedstatus');
        }

        if (empty($this->request->post['payment_jetpay_testmode'])) {
            if (empty($this->request->post['payment_jetpay_projectid'])) {
                $this->error['warning_projectid'] = $this->language->get('warning_projectid');
            }
            if (empty($this->request->post['payment_jetpay_secretkey'])) {
                $this->error['warning_secretkey'] = $this->language->get('warning_secretkey');
            }
        }

        return empty($this->error);
    }
}
