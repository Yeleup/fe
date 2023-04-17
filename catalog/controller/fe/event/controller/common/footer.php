<?php
class ControllerFeEventControllerCommonFooter extends Controller {
    public function before(&$route, &$args) {
        $route = 'fe/common/footer';
	}

	public function after(&$route, &$args, &$output) {
	}
}
