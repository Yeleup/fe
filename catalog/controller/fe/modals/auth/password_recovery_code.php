<?php
class ControllerFeModalsAuthPasswordRecoveryCode extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/auth/password_recovery_code', $data);
	}
}
