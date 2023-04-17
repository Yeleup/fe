<?php
class ControllerFeEventControllerCommonHeader extends Controller {
	public function after(&$route, &$args, &$output) {
		$output = preg_replace('/<img src="view\/image\/logo.png"/', '<img src="view/image/fe/event/common/logo.png" style="height: 100%" ', $output);
	}
}
