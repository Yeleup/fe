<?php
class ControllerFePagesDelivery extends Controller {
	private $errors = [];

	public function index() {
		$this->config->load('fe_config');

		if (!$this->isAllowed()) {
			$this->response->redirect($this->url->link('fe/pages/cart', '', true));
		}

		$this->document->setTitle("Адрес Доставки");

		$data['action'] = $this->url->link('fe/checkout/checkout', '', true);
		$data['button_links'] = $this->load->controller('fe/includes/pages/button_links');

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/delivery', $data));
	}

	public function isAllowed() {
		if ($this->cart->countProducts() <= 0) {
			$this->errors[] = 'Не выбрано деталей.';
		}
		if (!$this->cart->hasStock()) {
			$this->errors[] = 'Нет в наличии.';
		}

		if (!$this->errors) {
			return true;
		}
		return false;
	}
}
