<?php
class ControllerFeApiCatremoteYears extends Controller {

    public function index() {
        $model_id = $this->request->get['model_id'] ?? 0;
        $this->load->model('fe/tdcatalog/years');
        $result = $this->model_fe_tdcatalog_years->get($model_id);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($result);
    }

}
