<?php
class ControllerFeCommonCallback extends Controller {
	public function index() {
		$errors = $this->validate();
		$data['link_home'] = $this->url->link('fe/common/home');

		if ($errors) {
			$data['message'] = 'Ошибка.';
			$data['submessage'] = $errors[0];
		} else {
			$name = $this->request->post['name'];
			$phone = $this->request->post['phone'];

			$this->load->model('fe/customer/callback');
			$this->model_fe_customer_callback->add([
				'name' => $name,
				'phone' => $phone
			]);

			$data['message'] = 'Ваш запрос был принят.';
			$data['submessage'] = '';
		}

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/common/message', $data));
	}

	private function validate() {
		$errors = [];

		$this->request->post['name'] = $this->request->post['name'] ?? '';
		$this->request->post['phone'] = $this->request->post['phone'] ?? '';

		$phone = $this->request->post['phone'];
		$phone = preg_replace('/[^0-9]/i', '', $phone);
		$phone = '+7(' . substr($phone, 0, 3) . ')' . substr($phone, 3, 3)
			. '-' . substr($phone, 6, 2) . '-' . substr($phone, 8); // +7(777)777-77-77...
		$this->request->post['phone'] = $phone;

		if (!($this->request->post['name'] ?? false)) {
			$errors[] = 'Поле <<имя>> должно быть заполнено.';
		}

		if (!($this->request->post['phone'] ?? false)) {
			$errors[] = 'Поле <<телефон>> должно быть заполнено.';
		}

		if (strlen($this->request->post['phone']) < 16) {
			$errors[] = 'Поле <<телефон>> должен содержать 10 цифр.';
		}

		return $errors;
	}
}
