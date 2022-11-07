<?php
class ControllerFeCommonHeader extends Controller {
	public function index() {
        if (!$this->document->getDescription()) {
            $this->document->setDescription($this->config->get('config_meta_description'));
        }
		//$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}


		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['link_icon'] = $server . 'image/' . $this->config->get('config_icon');
		}

		$data['description'] = $this->document->getDescription();

		$data['customer_id'] = $this->session->data['customer_id'] ?? null;
		$this->load->model('account/customer');
		$data['customer'] = $this->model_account_customer->getCustomer($this->session->data['customer_id'] ?? 0) ?? null;

		$data['link_account'] = $this->url->link('fe/account/account', '', true);
		$data['link_orders'] = $this->url->link('fe/pages/history', '', true);
		$data['link_logout'] = $this->url->link('account/logout', '', true);
		$data['link_cart'] = $this->url->link('fe/pages/cart', '', true);
		$data['link_home'] = $this->url->link('fe/common/home', '', true);
		$data['link_about'] = $this->url->link('fe/common/about', '', true);
		$data['link_cooperation'] = $this->url->link('fe/common/cooperation', '', true);
		$data['link_contacts'] = $this->url->link('fe/common/contacts', '', true);
		$data['title'] = $this->document->getTitle();

		$notifications = $this->session->data['fe']['notifications'] ?? [];
		$this->session->data['fe']['notifications'] = [];
		$data['notification'] = $this->load->controller('fe/includes/common/notification', ['notifications' => $notifications]);

		$data['modal_auth'] = $this->load->controller('fe/modals/auth/auth');
		$data['modal_popup_image'] = $this->load->view('fe/modals/popup_image');

        $data['request_uri'] = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		return $this->load->view('fe/common/header', $data);
	}
}
