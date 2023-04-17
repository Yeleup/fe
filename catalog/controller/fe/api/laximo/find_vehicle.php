<?php
class ControllerFeApiLaximoFindVehicle extends Controller {
	public function index() {
		if (!isset($this->request->get['ident_string'])) {
			$this->error('Ident String');
			return;
		}

		$result = $this->json([
			'ident_string' => $this->request->get['ident_string']
		]);

		$json['data'] = $result;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getRedirect($args) {
		if (!isset($args['ident_string'])) {
			$this->error('Ident String');
			return;
		}

		$json = [];

		try {
			$result = $this->json([
				'ident_string' => $args['ident_string']
			]);
		} catch (\Exception $e) {
			$result = false;
		}



		if ($result) {
			$json['redirect'] = strval($result->row->_links->link_pages);
		}

		return $json;
	}

	public function json($args) {
		if (!$this->load->controller('fe/api/laximo/validate/customer')) return [];

		if (!isset($args['ident_string'])) {
			$this->error('Ident String');
			return;
		}

		$this->load->library('LaximoConnector');
		$soap = new LaximoConnectorOem();
		$query = 'FindVehicle:Locale=%s|IdentString=%s';
		$query = sprintf(
			$query,
			'ru_RU',
			$args['ident_string']
		);
		$result = $soap->query($query)->FindVehicle;

		if ($result->row) {
			$catalog = $result->row->attributes()->catalog;
			$vehicle_id = $result->row->attributes()->vehicleid;
			$ssd = $result->row->attributes()->ssd;

			$link_list_units = $this->url->link('fe/api/laximo/list_units', '', true) . '&catalog=%s&vehicle_id=%s&ssd=%s';
			$link = sprintf(
				$link_list_units,
				$catalog,
				$vehicle_id,
				$ssd
			);

			$result->row->addChild('_links');
			$result->row->_links->addChild('link', htmlspecialchars($link));


			$link_list_units_page = $this->url->link('fe/pages/list_units', '', true) . '&catalog=%s&vehicle_id=%s&ssd=%s';
			$link = sprintf(
				$link_list_units_page,
				$catalog,
				$vehicle_id,
				$ssd
			);
			$result->row->_links->addChild('link_pages', htmlspecialchars($link));

			return $result;
		}

		return false;
	}

	private function error($err) {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
	}
}
