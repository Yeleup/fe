<?php
class ControllerFeApiLaximoListUnits extends Controller {
	public function index() {
        if (!isset($this->request->get['catalog'])) {
            $this->error('Catalog');
            return;
        }
        if (!isset($this->request->get['vehicle_id'])) {
            $this->error('Vehicle ID');
            return;
        }
        if (!isset($this->request->get['ssd'])) {
            $this->error('SSD');
            return;
        }

        $json = $this->json([
            'catalog' => $this->request->get['catalog'],
            'vehicle_id' => $this->request->get['vehicle_id'],
            'ssd' => $this->request->get['ssd']
        ]);

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function json($args) {
		if (!$this->load->controller('fe/api/laximo/validate/customer')) return [];

        if (!isset($args['catalog'])) {
            $this->error('Catalog');
            return;
        }
        if (!isset($args['vehicle_id'])) {
            $this->error('Vehicle ID');
            return;
        }
        if (!isset($args['ssd'])) {
            $this->error('SSD');
            return;
        }

        $this->load->library('LaximoConnector');

		$soap = new LaximoConnectorOem();
		$query = 'ListUnits:Locale=%s|Catalog=%s|VehicleId=%s|CategoryId=-1|ssd=%s|Localized=true';
		$query = sprintf(
			$query,
			'ru_RU',
			$args['catalog'],
			$args['vehicle_id'],
			$args['ssd']
		);
		$result = $soap->query($query);

        $json = $result->ListUnits;

        /*$link_list_details_by_unit = $this->url->link('fe/api/laximo/list_detail_by_unit', '', true) . '&catalog=%s&unit_id=%s&ssd=%s';
        $link_list_details = $this->url->link('fe/pages/list_details', '', true) . '&catalog=%s&unit_id=%s&ssd=%s';*/
      
		$link_list_details_by_unit = $this->url->link('fe/api/laximo/list_detail_by_unit', '', true) . '?catalog=%s&unit_id=%s&ssd=%s';
        $link_list_details = $this->url->link('fe/pages/list_details', '', true) . '?catalog=%s&unit_id=%s&ssd=%s';
		
        foreach ($json->row as $item) {
            $item->addChild('_links');
            $link = sprintf(
                $link_list_details_by_unit,
                $this->request->get['catalog'],
                $item->attributes()->unitid,
                $item->attributes()->ssd
            );
            $item->_links->addChild('link', htmlspecialchars($link));

            $link = sprintf(
                $link_list_details,
                $this->request->get['catalog'],
                $item->attributes()->unitid,
                $item->attributes()->ssd
            );
            $item->_links->addChild('link_list_details', htmlspecialchars($link));
        }

		return $json;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
