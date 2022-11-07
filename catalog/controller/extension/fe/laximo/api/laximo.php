<?php

class ControllerExtensionFeLaximoApiLaximo extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->library('laximo');
        $this->response->addHeader('Content-Type: application/json');
    }

    public function listCatalogs() {
        $result = $this->laximo->listCatalogs();
        $this->response->setOutput(json_encode($result));
    }

    public function getWizard2() {
        $catalog = $this->request->get['catalog'] ?? '';
        $ssd = $this->request->get['ssd'] ?? '';
        $result = $this->laximo->getWizard2($catalog, $ssd);
        $this->response->setOutput(json_encode($result));
    }

    public function FindVehicleByWizard2() {
        $catalog = $this->request->get['catalog'] ?? '';
        $ssd = $this->request->get['ssd'] ?? '';
        $result = $this->laximo->FindVehicleByWizard2($catalog, $ssd);
        $this->response->setOutput(json_encode($result));
    }

    public function ListQuickGroup() {
        $catalog = $this->request->get['catalog'] ?? '';
        $vehicle_id = $this->request->get['vehicle_id'] ?? '';
        $ssd = $this->request->get['ssd'] ?? '';
        $result = $this->laximo->ListQuickGroup($catalog, $vehicle_id, $ssd);
        $this->response->setOutput(json_encode($result));
    }

    public function ListQuickDetail() {
        $catalog = $this->request->get['catalog'] ?? '';
        $vehicle_id = $this->request->get['vehicle_id'] ?? '';
        $quick_group_id = $this->request->get['quick_group_id'] ?? '';
        $ssd = $this->request->get['ssd'] ?? '';
        $result = $this->laximo->ListQuickDetail($catalog, $vehicle_id, $quick_group_id, $ssd);
        $this->response->setOutput(json_encode($result));
    }

    public function ListCategories() {
        $catalog = $this->request->get['catalog'] ?? '';
        $vehicle_id = $this->request->get['vehicle_id'] ?? '';
        $category_id = $this->request->get['category_id'] ?? '-1';
        $ssd = $this->request->get['ssd'] ?? '';
        $result = $this->laximo->ListCategories($catalog, $vehicle_id, $category_id, $ssd);
        $this->response->setOutput(json_encode($result));
    }

    public function ListUnits() {
        $catalog = $this->request->get['catalog'] ?? '';
        $vehicle_id = $this->request->get['vehicle_id'] ?? '';
        $category_id = $this->request->get['category_id'] ?? '-1';
        $ssd = $this->request->get['ssd'] ?? '';
        $result = $this->laximo->ListUnits($catalog, $vehicle_id, $category_id, $ssd);
        $this->response->setOutput(json_encode($result));
    }

    public function ListDetailByUnit() {
        $catalog = $this->request->get['catalog'] ?? '';
        $unit_id = $this->request->get['unit_id'] ?? '';
        $ssd = $this->request->get['ssd'] ?? '';
        $result = $this->laximo->ListDetailByUnit($catalog, $unit_id, $ssd);
        $this->response->setOutput(json_encode($result));
    }

    public function ListImageMapByUnit() {
        $catalog = $this->request->get['catalog'] ?? '';
        $unit_id = $this->request->get['unit_id'] ?? '';
        $ssd = $this->request->get['ssd'] ?? '';
        $result = $this->laximo->ListImageMapByUnit($catalog, $unit_id, $ssd);
        $this->response->setOutput(json_encode($result));
    }

    public function FindOem() {
        $oem = $this->request->get['oem'] ?? '';
        $result = $this->laximo->FindOem($oem);
        $this->response->setOutput(json_encode($result));
    }

    public function FindReplacements() {
        $detail_id = $this->request->get['detail_id'] ?? '';
        $result = $this->laximo->FindReplacements($detail_id);
        $this->response->setOutput(json_encode($result));
    }

    public function FindReplacementsOemOnly() {
        $detail_id = $this->request->get['detail_id'] ?? '';
        $result = $this->laximo->FindReplacementsOemOnly($detail_id);
        $this->response->setOutput(json_encode($result));
    }

    public function findVehicleByVin() {
        $catalog = $this->request->get['catalog'] ?? '';
        $vin = $this->request->get['vin'] ?? '';
        $result = $this->laximo->findVehicleByVin($catalog, $vin, true);
        $this->response->setOutput(json_encode($result));
    }

}
