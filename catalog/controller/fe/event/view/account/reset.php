<?php
class ControllerFeEventViewAccountReset extends Controller {
    public function before(&$route, &$args) {
	}

	public function after(&$route, &$args, &$output) {
        $data['action'] = $args['action'] ?? '';

        $data['footer'] = $this->load->controller('fe/common/footer');
        $data['header'] = $this->load->controller('fe/common/header');

        $output = $this->load->view('fe/account/reset', $data);
    }
}
