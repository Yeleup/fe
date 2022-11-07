<?php
class ControllerFeApiLaximoFindVehicleByWizard extends Controller {
	public function index() {
		if (!$this->load->controller('fe/api/laximo/validate/customer')) return [];

        if (!isset($this->request->get['catalog'])) {
            $this->error('Catalog');
            return;
        }

        if (!isset($this->request->get['ssd'])) {
            $this->error('SSD');
            return;
        }

        $this->load->library('LaximoConnector');
        $soap = new LaximoConnectorOem();
        $query = 'FindVehicleByWizard2:Locale=ru_RU|Catalog=' . $this->request->get['catalog'] . '|ssd=' . $this->request->get['ssd'];
        $result = $soap->query($query);

        $vehicles = [];
        $link_list_units = $this->url->link('fe/api/laximo/list_units', '', true) . '&catalog=%s&vehicle_id=%s&ssd=%s';
        foreach ($result->FindVehicleByWizard2->row as $item) {
            if (isset($item->attributes()->vehicleid)) {
                $id = strval($item->attributes()->vehicleid);
                $vehicles[$id] = $item;

                $link = sprintf(
                    $link_list_units,
                    $this->request->get['catalog'],
                    $id,
                    $item->attributes()->ssd
                );
                $item->addChild('_links');
                $item->_links->addChild('link', htmlspecialchars($link));
            }
        }

        $json['data'] = $vehicles;

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
