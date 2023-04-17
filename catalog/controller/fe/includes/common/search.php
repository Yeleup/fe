<?php
class ControllerFeIncludesCommonSearch extends Controller {
	public function index() {
        $data = [];
		$data['customer_id'] = $this->customer->isLogged();

		return $this->load->view('fe/includes/common/search', $data);
	}
}
