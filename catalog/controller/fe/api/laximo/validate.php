<?php
class ControllerFeApiLaximoValidate extends Controller {

    public function customer() {
        $customer_id = $this->session->data['customer_id'] ?? null;
        return $customer_id ? true : false;
        // return true;
    }

}
