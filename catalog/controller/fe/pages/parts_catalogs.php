<?php
class ControllerFePagesPartsCatalogs extends Controller {
	public function index() {
		$customer_id = $this->session->data['customer_id'] ?? 0;

		$this->load->model('fe/customer/fe_customer');
		$fe_customer = $this->model_fe_customer_fe_customer->getByCustomerId($customer_id);

		$this->load->model('fe/customer/customer_type');
		$customer_type = $this->model_fe_customer_customer_type->getById($fe_customer['customer_type_id'] ?? 0);
		$customer_type_name = $customer_type['name'] ?? '';

		$this->load->model('fe/util/log');
		$this->model_fe_util_log->log('parts_catalogs', "Customer with ID {$customer_id} has accessed parts_catalogs.");

		$data = [];

		$this->document->setTitle("Каталог");

		$pc_attempts = $_COOKIE['fe_parts_catalogs_attempts'] ?? 0;
		if ($customer_id) {
			$data['show_parts_catalogs'] = true;
		} elseif ($pc_attempts < 3) {
			setcookie("fe_parts_catalogs_attempts", $pc_attempts + 1, time() + (60 * 60 * 24));
			$data['show_parts_catalogs'] = true;
		}

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/parts_catalogs', $data));

	}
}
