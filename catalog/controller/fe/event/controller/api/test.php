<?php
class ControllerFeEventControllerApiTest extends Controller {
    public function before(&$route, &$args) {
        $route = 'fe/common/home';
	}

	public function after(&$route, &$args, &$output) {
    }
}
