<?php
class ControllerFeIncludesModalPublicOffer extends Controller {
	public function index() {
        $data = [];
        $this->response->setOutput($this->load->view('fe/includes/modalPublicOffer', $data));
	}
}
