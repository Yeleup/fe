<?php
class ControllerFeEventControllerAccountLogout extends Controller {
    public function before(&$route, &$args) {
	}

	public function after(&$route, &$args, &$output) {
        $this->response->redirect($this->url->link('fe/common/home'));
    }
}
