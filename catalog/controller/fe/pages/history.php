<?php
class ControllerFePagesHistory extends Controller {
	public function index() {
		$this->config->load('fe_config');

		$this->load->model('fe/checkout/order');
		$data['orders'] = $this->model_fe_checkout_order->get();

		$this->document->setTitle("История Заказов");

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/history', $data));
	}
}
