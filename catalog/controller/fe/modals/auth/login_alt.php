<?php
class ControllerFeModalsAuthLoginAlt extends Controller {
	public function index($args) {
		$avail_login_options = [
			'yandex',
			'google',
			'mail',
		];

        $data['login_option'] = $args['login_option'];

		if ($data['login_option'] === 'yandex') {
			$data['domain'] = 'yandex.ru';
		} elseif ($data['login_option'] === 'google') {
			$data['domain'] = 'google.com';
		} elseif ($data['login_option'] === 'mail') {
			$data['domain'] = 'mail.ru';
		}
        
		return $this->load->view('fe/modals/auth/login_alt', $data);
	}
}
