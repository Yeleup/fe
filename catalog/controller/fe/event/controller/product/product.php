<?php
class ControllerFeEventControllerProductProduct extends Controller {
    public function before(&$route, &$args) {
        $route = 'fe/pages/details';
	}

	public function after(&$route, &$args, &$output) {
	}
}
