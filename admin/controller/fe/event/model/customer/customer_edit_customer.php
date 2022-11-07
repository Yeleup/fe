<?php
class ControllerFeEventModelCustomerCustomerEditCustomer extends Controller {

    public function before(&$route, &$args) {
    }

	public function after(&$route, &$args, &$output) {
        $customer_id = $args[0];
        $customer_data = $args[1];

        $this->load->model('fe/customer/reg_type');
        $this->load->model('fe/customer/status');
        $this->load->model('fe/market/client_category');
        $this->load->model('fe/customer/fe_customer');
        $this->load->model('fe/customer/requisite');

        if ($customer_data['reg_type'] ?? false) {
            $customer_data['reg_type'] = $this->model_fe_customer_reg_type->getByName($customer_data['reg_type'])['id'];
        }

        if ($customer_data['fe_customer_status'] ?? false) {
            $customer_data['status'] = $this->model_fe_customer_status->getByName($customer_data['fe_customer_status'])['id'];
        }

        if (($customer_data['new_requisite'] ?? false) &&
            ($customer_data['new_requisite']['address'] ?? false) &&
            ($customer_data['new_requisite']['guid'] ?? false)) {

            $this->model_fe_customer_requisite->add([
                'customer_id' => $customer_id,
                'address' => $customer_data['new_requisite']['address'],
                'guid' => $customer_data['new_requisite']['guid']
            ]);
        }

        $this->model_fe_customer_fe_customer->updateById($customer_id, $customer_data);

        $f = true;
        foreach ($customer_data['requisites'] as $requisite_id => $requisite_data) {
            if ($requisite_data['delete'] ?? false) {
                $this->model_fe_customer_requisite->deleteById($requisite_id);
                continue;
            }

            $this->model_fe_customer_requisite->updateById($requisite_id, [
                'guid' => $requisite_data['guid'],
                'customer_id' => $customer_id,
                'address' => $requisite_data['address']
            ]);

            if ($f) {
                $client_category = $this->model_fe_market_client_category->getByRemoteRequisiteGuid($requisite_data['guid']);
                if ($client_category) {
                    $this->model_fe_customer_fe_customer->updateClientCategory($customer_id, $client_category['id'] ?? 0);
                }
                $f = false;
            }
        }
    }

}
