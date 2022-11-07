<?php
class ControllerFeApiLaximoVehicle extends Controller {
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

    public function json($args) {
		if (!$this->load->controller('fe/api/laximo/validate/customer')) return [];

        if (!isset($args['ident_string'])) {
            $this->error('Ident String');
            return;
        }

        $this->load->model('fe/laximo/catalog');
        $this->load->model('fe/laximo/vehicle');

        $result = $this->load->controller('fe/api/laximo/find_vehicle/json', [
            'ident_string' => $args['ident_string']
        ]);

        if ($result) {
            $catalog_code = $result->row->attributes()->catalog;
            $catalog_id = $this->model_fe_laximo_catalog->getIdByCode($catalog_code);
            if (!$catalog_id) {
                return false;
            }

            $vehicle_id = $this->model_fe_laximo_vehicle->getIdByCatalogIdAndModel($catalog_id, $result->row->attributes()->name);
        }

        $result = [
            'vehicle_id' => $vehicle_id
        ];

        return $result;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
