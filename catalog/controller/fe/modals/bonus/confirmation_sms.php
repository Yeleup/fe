<?php
class ControllerFeModalsBonusConfirmationSms extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/bonus/confirmation_sms', $data);
	}
}
