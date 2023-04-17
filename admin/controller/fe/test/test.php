<?php
class ControllerFeTestTest extends Controller {
	public function index() {
        echo "<pre>";
        $this->load->model('fe/market/price');
        $result = $this->model_fe_market_price->getPricesByProductId(52);
        // var_dump($this->load->controller('fe/api'));
        var_dump($result);
        echo "</pre>";
        $this->response->setOutput("HELELEOOEL");
	}
}
