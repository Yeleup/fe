<?php
class ControllerFeEventViewErrorNotFound extends Controller {
    public function before(&$route, &$args) {
        // $data['footer'] = $this->load->controller('fe/common/footer');
        // $data['header'] = $this->load->controller('fe/common/header');
        // $this->response->setOutput($this->load->view('fe/error/not_found', $data));
	}

	public function after(&$route, &$args, &$output) {
        $data['footer'] = $this->load->controller('fe/common/footer');
        $data['header'] = $this->load->controller('fe/common/header');

        $output = $this->load->view('fe/error/not_found', $data);
    }
}
