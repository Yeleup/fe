<?php
class ControllerFeAccountDetails extends Controller {

    public function index() {

        $customer_id = $this->session->data['customer_id'] ?? false;

        if (!$customer_id) {
            $this->response->redirect($this->url->link('fe/common/home', '', true));
        }

        $this->load->model('account/customer');
        $data['customer'] = $this->model_account_customer->getCustomer($customer_id);

        $data['action_edit'] = $this->url->link('account/edit', '', true);

        $data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

        $this->response->setOutput($this->load->view('fe/account/details', $data));
    }

}
