<?php
class ControllerFeIncludesModalPrivacyPolicy extends Controller {
	public function index() {
        $data = [];
        $this->response->setOutput($this->load->view('fe/includes/modalPrivacyPolicy', $data));
	}
}
