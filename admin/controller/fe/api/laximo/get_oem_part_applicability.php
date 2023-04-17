<?php
class ControllerFeApiLaximoGetOemPartApplicability extends Controller {
    public function index() {
        if (!isset($this->request->get['catalog'])) {
            $this->error('Catalog');
            return;
        }
        if (!isset($this->request->get['oem'])) {
            $this->error('OEM');
            return;
        }
        if (!isset($this->request->get['ssd'])) {
            $this->error('SSD');
            return;
        }

        $result = $this->json([
            'catalog' => $this->request->get['catalog'],
            'oem' => $this->request->get['oem'],
            'ssd' => $this->request->get['ssd']
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
        if (!isset($args['ssd'])) {
            return false;
        }

        $this->load->library('LaximoConnector');
        $soap = new LaximoConnectorOem();
        $query = 'GetOEMPartApplicability:OEM=%s|Catalog=%s|CategoryId=-1|ssd=%s|Locale=%s|All=1';
        $query = sprintf(
            $query,
            $args['oem'],
            $args['catalog'],
            $args['ssd'],
            'ru_RU',
        );
        $result = $soap->query($query)->GetOEMPartApplicability;

        return $result;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
