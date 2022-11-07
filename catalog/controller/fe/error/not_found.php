<?php
class ControllerFeErrorNotFound extends Controller {
	public function index() {
        $data['footer'] = $this->load->controller('fe/common/footer');
        $data['header'] = $this->load->controller('fe/common/header');
        $this->response->setOutput($this->load->view('fe/error/not_found', $data));
	}
}
