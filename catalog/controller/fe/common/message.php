<?php
class ControllerFeCommonMessage extends Controller {
	public function index() {
        $data['message'] = $this->request->get['message'] ?? '';
        $data['submessage'] = $this->request->get['submessage'] ?? '';

        $data['link_home'] = $this->url->link('fe/common/home', '', true);
		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/common/message', $data));
	}
}
