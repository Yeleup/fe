<?php
class ControllerFeApiMarketBalance extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/balance/get/list');

        $this->load->model('fe/market/balance');
        foreach ($result->message as $product) {
            $this->model_fe_market_balance->addBalance([
                'guid' => $product->guid,
                'quantity' => $product->balance
            ]);
        }
        return true;
    }

    public function one($product_id) {
        $this->load->model('fe/catalog/product');
        $guid = $this->model_fe_catalog_product->getGuidById($product_id);

        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/balance/get/' . $guid);
        
        $this->load->model('fe/market/balance');
        foreach ($result->message as $product) {
            $this->model_fe_market_balance->addBalance([
                'guid' => $product->guid,
                'quantity' => $product->balance
            ]);
        }
        return true;
    }
}
