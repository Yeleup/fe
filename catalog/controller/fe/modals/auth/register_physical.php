<?php
class ControllerFeModalsAuthRegisterPhysical extends Controller {
	public function index() {
        $data = [];
		$data['action_register'] = $this->url->link('fe/api/account/register', '', true);
		return $this->load->view('fe/modals/auth/register_physical', $data);
	}
}
