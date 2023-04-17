<?php
class ControllerFeApiMarketPrice extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $uri = '/market/productPrices/get/';

        $this->load->model('fe/market/price_for');
        $this->load->model('fe/market/price');
        $this->load->model('fe/catalog/product');

        $price_fors = $this->model_fe_market_price_for->get();

        foreach ($price_fors as $price_for) {
            if (!$price_for['guid']) {
                continue;
            }

            $result = $client->get($uri . $price_for['guid']);

            foreach ($result->message as $price) {

                $product = $this->model_fe_catalog_product->getProductByGuid($price->guid);

                if (!$product) {
                    continue;
                }

                $price_id = $this->model_fe_market_price->addPrice([
                    'price' => $price->price,
                    'product_id' => $product['product_id'],
                    'product_price_for_id' => $price_for['product_price_for_id']
                ]);

            }

        }

        return true;
    }
}
