<?php
class ControllerFeApiMarketCrosscode extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/crosscodes/get/list');

        $this->load->model('fe/catalog/product');
        $this->load->model('fe/market/crosscode');
        $this->load->model('fe/market/product_to_crosscode');

        if ($result) {
            $this->model_fe_market_product_to_crosscode->deleteAll();
            foreach ($result->message as $crosscodes) {
                try {
                    $product_by_guid = $this->model_fe_catalog_product->getProductByGuid($crosscodes->guid);
                    if (!$product_by_guid) {
                        continue;
                    }
                    $product_id = $product_by_guid['product_id'];
                    foreach ($crosscodes->crosscodes as $crosscode) {
                        $crosscode_id = $this->model_fe_market_crosscode->addCrosscode([
                            'crosscode' => $crosscode
                        ]);
                        $this->model_fe_market_product_to_crosscode->addProductToCrosscode([
                            'product_id' => $product_id,
                            'crosscode_id' => $crosscode_id,
                        ]);
                    }
                } catch (Throwable $e) {
                    echo $e->getMessage() . "\n";
                }
            }
        }

        return true;
    }
}
