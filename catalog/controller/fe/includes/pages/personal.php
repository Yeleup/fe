<?php
class ControllerFeIncludesPagesPersonal extends Controller {
	public function index() {
        $data = [];
		$this->load->model('account/customer');
		$this->load->model('fe/customer/status');

		if (isset($this->session->data['customer_id'])) {
			$data['customer'] = $this->model_account_customer->getCustomer($this->session->data['customer_id']);
		}

		$data['status_list'] = $this->model_fe_customer_status->get();

		return $this->load->view('fe/includes/pages/personal', $data);
	}
}
