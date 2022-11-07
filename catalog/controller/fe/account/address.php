<?php
class ControllerFeAccountAddress extends Controller {

    public function index() {

        $customer_id = $this->session->data['customer_id'] ?? false;

        if (!$customer_id) {
            $this->response->redirect($this->url->link('fe/common/home', '', true));
        }

        $region = $this->request->post['region'] ?? '';
        $town = $this->request->post['town'] ?? '';
        $street = $this->request->post['street'] ?? '';
        $house = $this->request->post['house'] ?? '';
        $entrance = $this->request->post['entrance'] ?? '';
        $apartment = $this->request->post['apartment'] ?? '';

        $this->load->model('fe/customer/address');

        if ($this->request->post['submit'] ?? false) {
            $this->model_fe_customer_address->add([
                'customer_id' => $customer_id,
                'region' => $region,
                'town' => $town,
                'street' => $street,
                'house' => $house,
                'entrance' => $entrance,
                'apartment' => $apartment
            ]);
        }

        $data['addresses'] = $this->model_fe_customer_address->getAllByCustomerId($customer_id);

        $data['action_add'] = $this->url->link('fe/account/address', '', true);
        $data['action_delete'] = $this->url->link('fe/account/address/delete', '', true);

        $data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

        $this->response->setOutput($this->load->view('fe/account/address', $data));
    }

    public function delete() {
        $customer_id = $this->session->data['customer_id'] ?? false;
        $address_id = $this->request->post['address_id'] ?? false;
        $this->load->model('fe/customer/address');
        if ($customer_id && $address_id) {
            $this->model_fe_customer_address->deleteByIdAndCustomerId($address_id, $customer_id);
        }
        $this->index();
    }

}
