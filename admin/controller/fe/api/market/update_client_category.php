<?php
class ControllerFeApiMarketUpdateClientCategory extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/update/get');

        if (!$result || $result->code != 200) {
            return false;
        }

        $this->load->model('fe/market/client_category');
        $this->load->model('fe/customer/requisite');
        $this->load->model('fe/customer/fe_customer');

        $clients = $result->message->clients ?? [];

        foreach ($clients as $client) {
            $client_category = $this->model_fe_market_client_category->getByGuid($client->category);
            $client_category_id = $client_category['id'] ?? 0;
            $requisites = $this->model_fe_customer_requisite->getByGuid($client->client);
            foreach ($requisites as $requisite) {
                $customer_id = $requisite['customer_id'] ?? 0;
                $this->model_fe_customer_fe_customer->updateClientCategory($customer_id, $client_category_id);
            }
        }

        return true;

    }
}
