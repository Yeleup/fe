<?php
class ControllerFeIncludesPagesPayment extends Controller {
	public function index() {
        $data = [];
		$this->load->model('account/customer');
		$this->load->model('fe/customer/fe_customer');
		$this->load->model('fe/customer/reg_type');

		$customer_id = $this->session->data['customer_id'] ?? null;

		if ($customer_id) {
			$data['customer'] = $this->model_account_customer->getCustomer($customer_id);
			$customer_data = $this->model_fe_customer_fe_customer->getByCustomerId($customer_id);
			$customer_reg_type = $this->model_fe_customer_reg_type->getById($customer_data['reg_type'] ?? 0);
			$customer_reg_type_name = $customer_reg_type['name'] ?? '';
			$data['showPaymentReserve'] = ($customer_reg_type_name === 'wholesale');
		}

		$data['showPaymentCard'] = true;

		return $this->load->view('fe/includes/pages/payment', $data);
	}
}
