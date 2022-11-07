<?php
class ControllerFeEventControllerAccountAccount extends Controller {
    public function before(&$route, &$args) {
	}

	public function after(&$route, &$args, &$output) {
        if ($this->session->data['customer_id'] ?? false) {
            $this->session->data['fe']['notifications'][] = [
                'header' => 'Успешная Авторизация',
                'body' => 'Авторизация прошла успешна.'
            ];
        }

        $this->response->redirect($this->url->link('fe/common/home'));
    }
}
