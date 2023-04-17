<?php
class ControllerFeModalsPaymentRefund extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/payment_refund', $data);
	}
}
