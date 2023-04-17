<?php
class ControllerFeModalsAuthPasswordChange extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/auth/password_change', $data);
	}
}
