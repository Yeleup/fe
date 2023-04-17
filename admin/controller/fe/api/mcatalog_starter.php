<?php
class ControllerFeApiMcatalogStarter extends Controller {

    public function classificator() {
        $result = $this->load->controller('fe/api/mcatalog/classificator');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
    }

}
