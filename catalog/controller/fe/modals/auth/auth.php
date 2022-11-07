<?php
class ControllerFeModalsAuthAuth extends Controller {
	public function index() {
        $data['modal_login'] = $this->load->controller('fe/modals/auth/login');
		$data['modal_register'] = $this->load->controller('fe/modals/auth/register');

		$data['modal_login_alt_yandex'] = $this->load->controller('fe/modals/auth/login_alt', [
			'login_option' => 'yandex'
		]);
		$data['modal_login_alt_google'] = $this->load->controller('fe/modals/auth/login_alt', [
			'login_option' => 'google'
		]);
		$data['modal_login_alt_mail'] = $this->load->controller('fe/modals/auth/login_alt', [
			'login_option' => 'mail'
		]);

		$data['modal_register_legal'] = $this->load->controller('fe/modals/auth/register_legal');
		$data['modal_register_physical'] = $this->load->controller('fe/modals/auth/register_physical');
		$data['modal_password_recovery'] = $this->load->controller('fe/modals/auth/password_recovery');
		$data['modal_recovery_sent'] = $this->load->controller('fe/modals/auth/recovery_sent');
		$data['modal_password_change'] = $this->load->controller('fe/modals/auth/password_change');
		$data['modal_password_recovery_code'] = $this->load->controller('fe/modals/auth/password_recovery_code');
		$data['modal_bonus_bonus_card'] = $this->load->controller('fe/modals/bonus/bonus_card');
		$data['modal_bonus_confirmation'] = $this->load->controller('fe/modals/bonus/confirmation');
		$data['modal_bonus_register'] = $this->load->controller('fe/modals/bonus/register');
		$data['modal_bonus_confirmation_sms'] = $this->load->controller('fe/modals/bonus/confirmation_sms');
		$data['modal_bonus_success'] = $this->load->controller('fe/modals/bonus/success');

		return $this->load->view('fe/modals/auth/auth', $data);
	}
}
