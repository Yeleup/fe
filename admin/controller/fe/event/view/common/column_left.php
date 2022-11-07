<?php
class ControllerFeEventViewCommonColumnLeft extends Controller {
    public function before(&$route, &$args) {

        $args['menus'][] = [
            'id' => 'menu-fe',
            'icon' => 'fa-ellipsis-v',
            'name' => 'Пятый Элемент',
            'href' => '',
            'children' => [
                [
                    'name' => 'Синхронизация',
                    'href' => $this->url->link('fe/market/synchronize', [
                        'user_token' => $this->session->data['user_token']
                    ], true),
                    'children' => [],
                ],
                [
                    'name' => 'Обратный звонок',
                    'href' => $this->url->link('fe/customer/callback', [
                        'user_token' => $this->session->data['user_token']
                    ], true),
                    'children' => [],
                ],
                [
                    'name' => 'Настройки Телеграм',
                    'href' => $this->url->link('fe/pages/telegram', [
                        'user_token' => $this->session->data['user_token']
                    ], true),
                    'children' => [],
                ],
                [
                    'name' => 'Logs',
                    'href' => $this->url->link('fe/pages/logs', [
                        'user_token' => $this->session->data['user_token']
                    ], true),
                    'children' => [],
                ],
            ]
        ];

    }

	public function after(&$route, &$args, &$output) {

    }
}
