<?php
class ControllerFeApiMarketBrand extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/brand/get/list');

        $this->load->model('fe/market/brand');
        foreach ($result->message as $brand) {
            $c = $this->model_fe_market_brand->getBrandByGuid($brand->guid);
            if (!$c) {
                $this->model_fe_market_brand->addBrand([
                    'guid' => $brand->guid,
                    'name' => $brand->name
                ]);
            }
        }
        return true;
    }
}
