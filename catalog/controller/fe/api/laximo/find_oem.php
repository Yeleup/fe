<?php
class ControllerFeApiLaximoFindOem extends Controller {
	public function index() {
        if (!isset($this->request->get['oem'])) {
            $this->error('OEM');
            return;
        }

        $json = $this->json([
            'oem' => $this->request->get['oem']
        ]);

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function json($args) {
		if (!$this->load->controller('fe/api/laximo/validate/customer')) return [];

        if (!isset($args['oem'])) {
            $this->error('OEM');
            return;
        }

        $this->load->library('LaximoConnector');
        $soap = new LaximoConnectorAm();
        $query = 'FindOEM:Locale=%s|OEM=%s|ReplacementTypes=Replacement|Options=crosses,weights,names,properties,images';
        $query = sprintf(
            $query,
            'ru_RU',
            $args['oem']
        );
        $result = $soap->query($query)->FindOEM;

        $json['data'] = $result;

        return $json;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
