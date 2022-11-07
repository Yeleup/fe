<?php
class ControllerFeApiLaximoSynchronize extends Controller {
    public function index() {
        $json = $this->json(100);

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

    public function json($limit = 10) {
        $this->load->model('fe/util');
        $this->load->model('fe/catalog/product');
        $this->load->model('fe/market/crosscode');
        $this->load->model('fe/market/product_to_crosscode');

        $offset = (int)$this->model_fe_util->getLaximoSynchronizeOffsetGet();
        $products = $this->model_fe_catalog_product->getByRange($offset, $limit);

        foreach ($products as $product) {
            $offset = $this->model_fe_util->getLaximoSynchronizeOffsetUpdate();

            $product_to_crosscodes = $this->model_fe_market_product_to_crosscode->getByProductId($product['product_id']);

            foreach ($product_to_crosscodes as $ptc) {
                $crosscode = $this->model_fe_market_crosscode->getById($ptc['crosscode_id']);

                if ($crosscode) {
                    $this->synchronize($product['product_id'], $crosscode['crosscode']);
                }

            }
        }

        return "Laximo синхронизировано.";
    }

    private function synchronize($product_id, $crosscode) {
        // Laximo
        $this->load->model('fe/laximo/catalog');
        $this->load->model('fe/laximo/vehicle');
        $this->load->model('fe/laximo/unit');

        $result_catalog = $this->load->controller('fe/api/laximo/find_part_references/json', [
            'oem' => $crosscode
        ]);

        if (!$result_catalog) {
            return false;
        }

        $catalog_ref = $result_catalog->OEMPartReference->CatalogReferences->CatalogReference;
        $catalog = $catalog_ref->attributes()->code;

        $data = [];
        $data['brand'] = $catalog_ref->attributes()->brand;
        $data['code'] = $catalog;
        $data['name'] = $catalog_ref->name;

        $this->model_fe_laximo_catalog->add($data);
        $catalog_id = $this->model_fe_laximo_catalog->getIdByCode($data['code']);

        $result_vehicle = $this->load->controller('fe/api/laximo/find_applicable_vehicles/json', [
            'oem' => $crosscode,
            'catalog' => $catalog
        ]);

        foreach ($result_vehicle->row as $vehicle) {
            $data = [];
            $data['vehicle_id'] = $vehicle->attributes()->vehicleid;
            $data['catalog_id'] = $catalog_id;
            $data['model'] = $vehicle->attributes()->name;
            $data['year'] = '';

            if ($vehicle->attribute) {
                // $f = true;

                foreach ($vehicle->attribute as $attr) {
                    if (in_array(strval($attr->attributes()->key), ['prodPeriod', 'manufactured'])) {
                        $data['year'] = $attr->attributes()->value;
                        // $f = false;
                        break;
                    }
                }

                // if ($f) {
                //     echo "<pre>";
                //     var_dump($vehicle);
                //     echo "</pre>";
                // }
            }

            // echo "<pre>";
            // var_dump($data);
            // echo "</pre>";

            $this->model_fe_laximo_vehicle->add($data);
            $vehicle_id = $this->model_fe_laximo_vehicle->getIdByUnique($data['catalog_id'], $data['model'], $data['year']);

            $ssd = $vehicle->attributes()->ssd;

            $result_unit = $this->load->controller('fe/api/laximo/get_oem_part_applicability/json', [
                'oem' => $crosscode,
                'catalog' => $catalog,
                'ssd' => $ssd
            ]);

            foreach ($result_unit->Category->Unit as $unit) {
                $data = [];
                $data['unit_id'] = $unit->attributes()->unitid;
                $data['vehicle_id'] = $vehicle_id;
                $data['name'] = $unit->attributes()->name;
                $data['product_id'] = $product_id;

                $this->model_fe_laximo_unit->add($data);
                $unit_id = $this->model_fe_laximo_unit->getIdByUnitId($data['unit_id']);
            }
        }

        $this->load->model('fe/laximo/cleanup');
        $this->model_fe_laximo_cleanup->clean();

        return true;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
