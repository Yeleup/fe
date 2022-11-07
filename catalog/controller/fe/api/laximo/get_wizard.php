<?php
class ControllerFeApiLaximoGetWizard extends Controller {
	public function index() {
		if (!isset($this->request->get['catalog'])) {
			$this->error();
			return;
		}

		$result = $this->json();

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($result));
	}

	public function json() {
		if (!$this->load->controller('fe/api/laximo/validate/customer')) return [];

		if (!isset($this->request->get['catalog'])) {
			$this->error();
			return;
		}

		$this->load->library('LaximoConnector');
		$soap = new LaximoConnectorOem();
		$query = 'GetWizard2:Locale=en_US|Catalog=' . $this->request->get['catalog'] . '|ssd=';
		$result = $soap->query($query);

		$json = [];
		foreach ($result->GetWizard2->row as $item) {
			if (in_array(strtolower($item->attributes()->name), [
				'model', 'series', 'market',
				'family', 'region', 'vehicle family'
				])) {
				$json['data'] = $item;
				break;
			}
		}

		if ($json != null) {
			$link_find_vehicle_by_wizard = $this->url->link('fe/api/laximo/find_vehicle_by_wizard', '', true) . '&catalog=%s&ssd=%s';
			foreach ($json['data']->options->row as $item) {
				$item->addChild('_links');
				$link = sprintf(
					$link_find_vehicle_by_wizard,
					$this->request->get['catalog'],
					$item->attributes()->key
				);
				$item->_links->addChild('link', htmlspecialchars($link));
			}
		} else {
			$json['data'] = 'none';
		}

		return $json;
	}

	private function error() {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(['error' => 'Catalog is not set.']));
	}
}
