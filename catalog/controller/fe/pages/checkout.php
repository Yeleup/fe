<?php
class ControllerFePagesCheckout extends Controller {

    private $errors = [];



	public function index() {
		$this->config->load('fe_config');

		if (!$this->isAllowed()) {
			$this->response->redirect($this->url->link('fe/pages/cart', '', true));
		}

		$this->document->setTitle("Доставка");

		$data['action'] = $this->url->link('fe/checkout/checkout', '', true);
		$data['button_links'] = $this->load->controller('fe/includes/pages/button_links');

        $data['link_cart'] = $this->url->link('fe/pages/cart', '', true);

        $data['tab_delivery'] = $this->load->controller('fe/includes/pages/delivery');
        $data['tab_personal'] = $this->load->controller('fe/includes/pages/personal');
        $data['tab_requisites'] = $this->load->controller('fe/includes/pages/requisites');
        $data['tab_payment'] = $this->load->controller('fe/includes/pages/payment');

        $data['modal_alarm'] = $this->load->view('fe/modals/checkout/alarm');

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/checkout', $data));
	}



    public function isAllowed() {
        $this->load->model('fe/customer/fe_customer');
		$fe_customer = $this->model_fe_customer_fe_customer->getByCustomerId($this->session->data['customer_id'] ?? '');

		$this->load->model('fe/customer/customer_type');
		$customer_type = $this->model_fe_customer_customer_type->getById($fe_customer['customer_type_id'] ?? '');
        $customer_type_name = $customer_type['name'] ?? '';

		if ($this->cart->countProducts() <= 0) {
			$this->errors[] = 'Не выбрано деталей.';
		}
		if ($customer_type_name != 'business' && !$this->cart->hasStock()) {
			$this->errors[] = 'Нет в наличии.';
		}

		if (!$this->errors) {
			return true;
		}
		return false;
	}

}
