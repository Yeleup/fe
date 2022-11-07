<?php
class ControllerFeModalsBonusSuccess extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/bonus/success', $data);
	}
}
