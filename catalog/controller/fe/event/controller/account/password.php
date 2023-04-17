<?php
class ControllerFeEventControllerAccountPassword extends Controller {
    public function before(&$route, &$args) {
	}

	public function after(&$route, &$args, &$output) {
        $this->response->redirect($this->url->link('fe/pages/account', '', true));
    }
}
