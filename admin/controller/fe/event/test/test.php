<?php
class ControllerFeEventTestTest extends Controller {
    public function before(&$route, &$args) {
        var_dump("before");
    }

	public function after(&$route, &$args, &$output) {
        var_dump("after");
    }
}
