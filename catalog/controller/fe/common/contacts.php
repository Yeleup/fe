<?php
class ControllerFeCommonContacts extends Controller {
	public function index() {
		$this->config->load('fe_config');

		$this->document->setTitle("Контактные данные компании Пятый элемент в г. Алматы");
		$this->document->setDescription("Контактные данные компании Пятый элемент в г. Алматы. Свяжитесь с нами любым удобным для вас способом. Мы уже более 20 лет поставляем запчасти для корейских и японских авто.");
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['config_map_api_key'] = $this->config->get('fe_map_api_key');

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/common/contacts', $data));
	}
}
