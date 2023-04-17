<?php
class ControllerFeEventControllerAccountForgotten extends Controller {
    public function before(&$route, &$args) {
        if (
            ($this->request->post['email'] ?? false)
        ) {
            $this->session->data['fe']['notifications'][] = [
                'header' => '',
                'body' => 'Ссылка на восстановление пароля отправлена на почту.'
            ];
        }
    }

    public function after(&$route, &$args, &$output) {
    }
}
