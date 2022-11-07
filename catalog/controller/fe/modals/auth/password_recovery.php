<?php
class ControllerFeModalsAuthPasswordRecovery extends Controller {
	public function index() {
		$data = [];
		$data['action'] = $this->url->link('account/forgotten', '', true);
		return $this->load->view('fe/modals/auth/password_recovery', $data);
	}
}
