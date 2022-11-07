<?php
class ControllerFeApiCatremoteModels extends Controller {

    public function index() {
        $brand_id = $this->request->get['brand_id'] ?? 0;
        $this->load->model('fe/tdcatalog/models');
        $result = $this->model_fe_tdcatalog_models->get($brand_id);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($result);
    }

}
