<?php
class ControllerFeApiAccountRegister extends Controller {
    private $error = array();

	public function index() {
        $this->load->model('account/customer');
        $this->load->model('fe/customer/fe_customer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $customer_id = $this->model_account_customer->addCustomer($this->request->post);
            $fe_customer_result = $this->model_fe_customer_fe_customer->add([
                'customer_id' => $customer_id,
                'reg_type' => $this->request->post['reg_type'],
                'status' => $this->request->post['status'],
                'iin' => ($this->request->post['iin'] ?? null),
                'company_name' => ($this->request->post['company_name'] ?? null),
                'company_name_full' => ($this->request->post['company_name_full'] ?? null),
                'official_address' => ($this->request->post['official_address'] ?? null),
                'address' => ($this->request->post['address'] ?? null),
            ]);

            if ($fe_customer_result) {
                $this->load->model('fe/util/telegram');
                $this->model_fe_util_telegram->sendNotifications("Пользователь {$this->request->post['email']} был зарегистрирован.");
            }

            // Clear any previous login attempts for unregistered accounts.
            $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

            $this->customer->login($this->request->post['email'], $this->request->post['password']);

            unset($this->session->data['guest']);

            $this->response->redirect($this->url->link('fe/common/home'));
        }

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode([
            'code' => 400,
            'message' => $this->error
        ]));
    }



    private function validate() {
        if ( !in_array($this->request->post['reg_type'], ['retail', 'wholesale']) ) {
            $this->error['reg_type'] = 'Ошибка Типа Регистарации';
        }

        if ( !in_array($this->request->post['status'], ['legal', 'physical']) ) {
            $this->error['status'] = 'Ошибка Статуса';
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		// Customer Group
		if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->post['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'account') {
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
					$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
					$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}
		}

		if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}

		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['captcha'] = $captcha;
			}
		}

		// Agree to terms
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		return !$this->error;
    }

}
