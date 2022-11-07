<?php
class ControllerFeEventControllerAccountLogin extends Controller {
    public function before(&$route, &$args) {
        if (
            $this->request->server['REQUEST_METHOD'] == 'POST' &&
            ($this->request->post['email'] ?? false) &&
            ($this->request->post['password'] ?? false)
        ) {
            $this->session->data['fe']['login_attempt'] = true;
        }
	}

	public function after(&$route, &$args, &$output) {
        if ($this->session->data['fe']['login_attempt'] ?? false) {
            $this->session->data['fe']['notifications'][] = [
                'header' => 'Ошибка Авторизации',
                'body' => 'Произошла ошибка во время авторизации.'
            ];
            $this->session->data['fe']['login_attempt'] = false;
        }

        $this->response->redirect($this->url->link('fe/common/home'));
    }
}
