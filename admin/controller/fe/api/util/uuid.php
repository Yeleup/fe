<?php
class ControllerFeApiUtilUuid extends Controller {

    public function generate() {
        $this->load->model('fe/util/uuid');
        $result = $this->model_fe_util_uuid->generate();
        return ['uuid' => $result];
    }

}
