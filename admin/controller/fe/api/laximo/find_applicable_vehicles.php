<?php
class ControllerFeApiLaximoFindApplicableVehicles extends Controller {
    public function index() {
        if (!isset($this->request->get['catalog'])) {
            $this->error('Catalog');
            return;
        }
        if (!isset($this->request->get['oem'])) {
            $this->error('OEM');
            return;
        }

        $result = $this->json([
            'catalog' => $this->request->get['catalog'],
            'oem' => $this->request->get['oem']
        ]);

        $json['data'] = $result;

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

    public function json($args) {
        if (!isset($args['catalog'])) {
            return false;
        }
        if (!isset($args['oem'])) {
            return false;
        }

        $this->load->library('LaximoConnector');
        $soap = new LaximoConnectorOem();
        $query = 'FindApplicableVehicles:OEM=%s|Catalog=%s|ssd=|Locale=%s';
        $query = sprintf(
            $query,
            $args['oem'],
            $args['catalog'],
            'ru_RU',
        );
        $result = $soap->query($query)->FindApplicableVehicles;

        $link_get_oem_part_applicability = $this->url->link('fe/api/laximo_starter/getOemPartApplicability', '', true) . '&user_token=%s&oem=%s&catalog=%s&ssd=%s';
        foreach ($result->row as $vehicle) {
            $link_get_oem_part_applicability = sprintf(
                $link_get_oem_part_applicability,
                $this->session->data['user_token'],
                $args['oem'],
                $args['catalog'],
                $vehicle->attributes()->ssd
            );

            $vehicle->addChild('_links');
            $link = htmlspecialchars($link_get_oem_part_applicability);
            $vehicle->_links->addChild('GetOemPartApplicability', $link);
        }
        
        return $result;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
