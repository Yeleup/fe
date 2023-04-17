<?php
class ControllerFeApiMarketDeliveryPrice extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/delivery');
        if ($result->code == 200) {
            $delivery_price = $result->message->value ?? null;
            if ($delivery_price) {
                $this->load->model('fe/util/util');
                $this->model_fe_util_util->upsert('delivery_price', $delivery_price, '');
                return true;
            }
        } else {
            return false;
        }
    }
}
