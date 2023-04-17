<?php
class ControllerFeEventControllerApiOrderUpdateStatus extends Controller {
    public function before(&$route, &$args) {
        $route = 'fe/api/market_in/api_order/update_status';
	}

	public function after(&$route, &$args, &$output) {
    }
}
