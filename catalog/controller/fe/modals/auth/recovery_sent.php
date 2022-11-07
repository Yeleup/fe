<?php
class ControllerFeModalsAuthRecoverySent extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/auth/recovery_sent', $data);
	}
}
