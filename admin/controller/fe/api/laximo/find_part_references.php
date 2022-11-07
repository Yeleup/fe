<?php
class ControllerFeApiLaximoFindPartReferences extends Controller {
    public function index() {
        if (!isset($this->request->get['oem'])) {
            $this->error('OEM');
            return;
        }

        $result = $this->json([
            'oem' => $this->request->get['oem']
        ]);

        $json['data'] = $result;

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

    public function json($args) {
        if (!isset($args['oem'])) {
            return false;
        }

        $this->load->library('LaximoConnector');
        $soap = new LaximoConnectorOem();
        $query = 'FINDPARTREFERENCES:Locale=%s|OEM=%s';
        $query = sprintf(
            $query,
            'ru_RU',
            $args['oem']
        );
        $result = $soap->query($query)->OEMPartReferences;

        if (!$result->OEMPartReference->count()) {
            return null;
        }

        $link_find_applicable_vehicles = $this->url->link('fe/api/laximo_starter/findApplicableVehicles', '', true) . '&user_token=%s&oem=%s&catalog=%s';
        $link_find_applicable_vehicles = sprintf(
            $link_find_applicable_vehicles,
            $this->session->data['user_token'],
            $args['oem'],
            $result->OEMPartReference->CatalogReferences->CatalogReference->attributes()->code
        );

        $catalog_ref = $result->OEMPartReference->CatalogReferences->CatalogReference;

        $catalog_ref->addChild('_links');
        $link = htmlspecialchars($link_find_applicable_vehicles);
        $catalog_ref->_links->addChild('FindApplicableVehicles', $link);

        return $result;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
