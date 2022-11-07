<?php
class ControllerFePagesRequisites extends Controller {
	public function index() {
		$this->config->load('fe_config');

		$this->document->setTitle("Реквизиты");

		$data['button_links'] = $this->load->controller('fe/includes/pages/button_links');

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');
		
		$this->response->setOutput($this->load->view('fe/pages/requisites', $data));
	}
}
