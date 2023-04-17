<?php
class ControllerFePagesPersonal extends Controller {
	public function index() {
		$this->config->load('fe_config');
		$this->load->model('account/customer');
		$this->document->setTitle("Личные Данные");

		if (isset($this->session->data['customer_id'])) {
			$data['customer'] = $this->model_account_customer->getCustomer($this->session->data['customer_id']);
		}

		$data['button_links'] = $this->load->controller('fe/includes/pages/button_links');

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/personal', $data));
	}
}
