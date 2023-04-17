<?php
class ControllerFeEventControllerErrorNotFound extends Controller {
    public function before(&$route, &$args) {
        var_dump("HELLO");
        die();
        $route = 'fe/common/home';
	}

	public function after(&$route, &$args, &$output) {
	}
}
