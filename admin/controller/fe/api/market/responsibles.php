<?php
class ControllerFeApiMarketResponsibles extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/responsible');

        $this->load->model('fe/market/responsible');

        foreach ($result->message as $val) {
            $this->model_fe_market_responsible->add([
                'guid' => $val->guid,
                'name' => $val->name
            ]);
        }

        return true;
    }
}
