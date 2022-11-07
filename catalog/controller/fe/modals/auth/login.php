<?php
class ControllerFeModalsAuthLogin extends Controller {
	public function index() {
		$data['login_action'] = $this->url->link('account/login', '', true);

		if (isset($this->request->request['route'])) {
			$url_params = $this->request->request;
			unset($url_params['route']);
			$data['login_redirect'] = $this->url->link($this->request->request['route'], $url_params, true);
		}

		return $this->load->view('fe/modals/auth/login', $data);
	}
}
