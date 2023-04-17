<?php
class ControllerFeApiUtil extends Controller {

    public function uuid_generate() {
        $result = $this->load->controller('fe/api/util/uuid/generate');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
    }

}
