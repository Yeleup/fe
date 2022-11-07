<?php
class ControllerFeApiMarketClientCategory extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/clientsCategories/get');

        $this->load->model('fe/market/client_category');

        foreach ($result->message as $cc) {
            $result = $this->model_fe_market_client_category->add([
                'guid' => $cc->guid,
                'name' => $cc->name
            ]);
        }

        return true;
    }
}
