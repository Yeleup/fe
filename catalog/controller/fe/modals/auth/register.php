<?php
class ControllerFeModalsAuthRegister extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/auth/register', $data);
	}
}
