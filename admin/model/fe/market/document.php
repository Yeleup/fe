<?php
class ModelFeMarketDocument extends Model {

    public function set($data) {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->post('/market/document/set', $data);
        return $result;
    }

}
