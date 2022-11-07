<?php
class ControllerFeApiCatremoteBrands extends Controller {

    public function index() {
        $this->load->model('fe/tdcatalog/brands');
        $result = $this->model_fe_tdcatalog_brands->get();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($result);
    }

}
