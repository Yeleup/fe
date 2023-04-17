<?php
class ControllerFeApiLaximoLaximoLocal extends Controller {
    public function catalog() {
        $this->load->model('fe/laximo/laximo');

        $result = $this->model_fe_laximo_laximo->getCatalogs();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
    }

    public function vehicle() {
        if (!isset($this->request->get['catalog_id']) || !isset($this->request->get['model'])) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode([
                'status' => 'error'
            ]));
        } else {

            $this->load->model('fe/laximo/vehicle');

            $result = $this->model_fe_laximo_vehicle->getByCatalogIdAndModel($this->request->get['catalog_id'], $this->request->get['model']);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($result));
        }
    }

    public function vehicleModels() {
        if (!isset($this->request->get['catalog_id'])) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode([
                'status' => 'error'
            ]));
        } else {

            $this->load->model('fe/laximo/vehicle');

            $result = $this->model_fe_laximo_vehicle->getModelsByCatalogId($this->request->get['catalog_id']);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($result));
        }
    }
}