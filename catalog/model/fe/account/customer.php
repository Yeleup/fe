<?php
class ModelFeAccountCustomer extends Model {

    public function get($id) {
        $this->load->model('account/customer');
        return $this->model_account_customer->getCustomer($id);
    }

}
