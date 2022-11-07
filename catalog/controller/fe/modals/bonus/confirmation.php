<?php
class ControllerFeModalsBonusConfirmation extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/bonus/confirmation', $data);
	}
}
