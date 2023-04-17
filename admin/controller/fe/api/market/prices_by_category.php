<?php
class ControllerFeApiMarketPricesByCategory extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/pricesByCategory/get');

        $this->load->model('fe/market/prices_by_category');
        $this->load->model('fe/market/price_for');
        $this->load->model('fe/market/client_category');
        $this->load->model('fe/catalog/category');

        foreach ($result->message as $pbc) {
            $pbc_id = $this->model_fe_market_prices_by_category->add([
                'price_for_id' => $this->model_fe_market_price_for->getIdByGuid($pbc->price),
                'client_category_id' => $this->model_fe_market_client_category->getByGuid($pbc->categoryClient)['id'] ?? 0,
                'category_id' => $this->model_fe_catalog_category->getIdByGuid($pbc->categoryProduct),
                'percent' => $pbc->percent
            ]);
        }

        return true;
    }
}
