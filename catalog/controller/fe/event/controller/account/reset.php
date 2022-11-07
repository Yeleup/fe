<?php
class ControllerFeEventControllerAccountReset extends Controller {
    public function before(&$route, &$args) {
        if (
            ($this->request->post['password'] ?? false) &&
            ($this->request->post['password'] ?? confirm)
        ) {
            $this->session->data['fe']['notifications'][] = [
                'header' => 'Пароль восстановлен',
                'body' => 'Пароль успешно восстановлен.'
            ];
        }
	}

	public function after(&$route, &$args, &$output) {
    }
}
