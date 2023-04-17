<?php
class ControllerFeAccountAccount extends Controller {

    public function index() {

        $customer_id = $this->session->data['customer_id'] ?? false;

        if (!$customer_id) {
            $this->response->redirect($this->url->link('fe/common/home', '', true));
        }

        $this->load->model('account/customer');
        $data['customer'] = $this->model_account_customer->getCustomer($customer_id);

        $data['link_account_details'] = $this->url->link('fe/account/details', '', true);
        $data['link_account_password'] = $this->url->link('fe/account/password', '', true);
        $data['link_account_requisites'] = $this->url->link('fe/account/requisites', '', true);
        $data['link_account_address'] = $this->url->link('fe/account/address', '', true);

        $data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

        $this->response->setOutput($this->load->view('fe/account/account', $data));
    }

}
