<?php
class ControllerFeIncludesModalPaymentRefund extends Controller {
	public function index() {
        $data = [];
        $this->response->setOutput($this->load->view('fe/includes/modalPaymentRefund', $data));
	}
}
