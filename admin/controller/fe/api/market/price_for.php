<?php
class ControllerFeApiMarketPriceFor extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/prices/get/list');

        $this->load->model('fe/market/price_for');

        foreach ($result->message as $price) {
            $price_for_id = $this->model_fe_market_price_for->addPriceFor([
                'guid' => $price->guid,
                'name' => $price->name,
            ]);
        }

        return true;
    }
}
