<?php
class ControllerFeIncludesCommonSearchLaximo extends Controller {
	public function index() {
        $data = [];
		//$data['customer_id'] = $this->session->data['customer_id'] ?? 0;
		$data['customer_id'] = $this->customer->isLogged();
		
		return $this->load->view('fe/includes/common/search_laximo', $data);
	}
}
