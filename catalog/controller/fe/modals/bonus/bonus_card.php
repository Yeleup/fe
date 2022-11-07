<?php
class ControllerFeModalsBonusBonusCard extends Controller {
	public function index() {
        $data = [];
		return $this->load->view('fe/modals/bonus/bonus_card', $data);
	}
}
