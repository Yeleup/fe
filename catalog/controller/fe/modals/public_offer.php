<?php
class ControllerFeModalsPublicOffer extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/public_offer', $data);
	}
}
