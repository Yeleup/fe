<?php
class ControllerFeEventControllerCommonHeader extends Controller {
    public function before(&$route, &$args) {
        $route = 'fe/common/header';
	}

	public function after(&$route, &$args, &$output) {
	}
}
