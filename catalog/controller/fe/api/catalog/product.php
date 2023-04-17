<?php
class ControllerFeApiCatalogProduct extends Controller {
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
        if (!isset($args['oem'])) {
            $this->error('OEM');
            return;
        }

        $json = $this->load->controller('fe/api/market/crosscodes/json', ['oem' => $args['oem']]);

        if (!$json) {
            return null;
        }

        $this->load->model('fe/market/crosscode');
        $json = $this->model_fe_market_crosscode->getByCrosscodes($json['data']);

        if (!$json) {
            return null;
        }

        $ids = [];
        foreach ($json as $crosscode) {
            $ids[] = $crosscode['product_crosscode_id'];
        }

        $this->load->model('fe/market/product_to_crosscode');
        $json = $this->model_fe_market_product_to_crosscode->getByCrosscodeIds($ids);

        if (!$json) {
            return null;
        }

        $ids = [];
        foreach ($json as $product_to_crosscode) {
            $ids[] = $product_to_crosscode['product_id'];
        }

        $this->load->model('fe/catalog/product');
        $json = $this->model_fe_catalog_product->getByIds($ids);

        if (!$json) {
            return null;
        }

        return $json;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
