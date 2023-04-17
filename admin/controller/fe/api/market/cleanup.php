<?php
class ControllerFeApiMarketCleanup extends Controller {
    public function index() {
        $this->load->model('fe/market/cleanup');
        $this->model_fe_market_cleanup->clean();
        return true;
    }
}
