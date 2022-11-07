<?php
class ControllerFeModalsPrivacyPolicy extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/privacy_policy', $data);
	}
}
