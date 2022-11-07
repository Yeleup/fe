<?php
class ControllerFeIncludesPagesRequisites extends Controller {
	public function index() {
        $data = [];

		$customer_id = $this->session->data['customer_id'] ?? 0;

		if ($customer_id) {
			$this->load->model('fe/customer/requisite');
			$data['requisites'] = $this->model_fe_customer_requisite->getByCustomerId($customer_id);
		}

		return $this->load->view('fe/includes/pages/requisites', $data);
	}
}
