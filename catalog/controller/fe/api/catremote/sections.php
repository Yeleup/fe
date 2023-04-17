<?php
class ControllerFeApiCatremoteSections extends Controller {

    public function index() {
        $modification_id = $this->request->get['modification_id'] ?? 0;
        $parent_id = $this->request->get['parent_id'] ?? null;
        $this->load->model('fe/tdcatalog/sections');
        $result = $this->model_fe_tdcatalog_sections->get($modification_id, $parent_id);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($result);
    }

}
