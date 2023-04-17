<?php
class ControllerFeIncludesPagesDelivery extends Controller {

	public function index() {
		$data = [];

		$this->load->model('fe/util/util');
		$this->load->model('fe/customer/address');

		$data['delivery_price'] = $this->model_fe_util_util->getByName('delivery_price')['int_val'] ?? '1000';
		$data['addresses'] = $this->model_fe_customer_address->getAllByCustomerId($this->session->data['customer_id'] ?? '');

		return $this->load->view('fe/includes/pages/delivery', $data);
	}

}
