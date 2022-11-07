<?php
class ControllerFeModalsBonusRegister extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/bonus/register', $data);
	}
}
