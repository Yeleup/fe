<?php
class ControllerFeAccountRequisites extends Controller {

    public function index() {

        $customer_id = $this->session->data['customer_id'] ?? false;

        if (!$customer_id) {
            $this->response->redirect($this->url->link('fe/common/home', '', true));
        }

        $this->load->model('fe/customer/requisite');

        // POST Requisites
        if (
            ($this->request->post['company_full_name'] ?? false) &&
            ($this->request->post['idNumber'] ?? false) &&
            ($this->request->post['legal_address'] ?? false)
        ) {
            if ($customer_id) {
                $requisite = [];
                if (!empty($this->request->post['company_full_name'])) $requisite[] = 'Название компании: ' . $this->request->post['company_full_name'];
                if (!empty($this->request->post['idNumber'])) $requisite[] = 'БИН или ИИН: ' . $this->request->post['idNumber'];
                if (!empty($this->request->post['legal_address'])) $requisite[] = 'Юридический адрес: ' . $this->request->post['legal_address'];

                if ($requisite) {
                    $requisite = implode(', ', $requisite);

                    $requisite_id = $this->model_fe_customer_requisite->add([
                        'address' => $requisite,
                        'customer_id' => $customer_id
                    ]);
                }
            }
        } // POST Requisites


        // Get Requisites
        if ($customer_id) {
			$this->load->model('fe/customer/requisite');
			$data['requisites'] = $this->model_fe_customer_requisite->getByCustomerId($customer_id);
		} // Get Requisites

        $this->load->model('account/customer');
        $data['customer'] = $this->model_account_customer->getCustomer($customer_id);

        $data['action_requisites'] = $this->url->link('fe/account/requisites', '', true);
        $data['action_requisites_delete'] = $this->url->link('fe/account/requisites/delete', '', true);

        $data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

        $this->response->setOutput($this->load->view('fe/account/requisites', $data));
    }

    public function delete() {
        $customer_id = $this->session->data['customer_id'] ?? false;

        if (!$customer_id) {
            $this->response->redirect($this->url->link('fe/common/home', '', true));
        }

        $requisite_id = $this->request->post['requisite_id'] ?? false;

        if ($requisite_id && $customer_id) {
            $this->load->model('fe/customer/requisite');
            $result = $this->model_fe_customer_requisite->deleteByIdAndCustomerId($requisite_id, $customer_id);
        }

        $this->index();
    }

}
