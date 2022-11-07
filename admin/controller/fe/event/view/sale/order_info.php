<?php
class ControllerFeEventViewSaleOrderInfo extends Controller {

    public function before(&$route, &$args) {
    }



	public function after(&$route, &$args, &$output) {
        $order_id = $this->request->get['order_id'] ?? 0;
        $user_token = $this->session->data['user_token'] ?? '';

        $search_str = '#<div class="pull-right">#m';
        $insert_str = '
        <a href="index.php?route=fe/api/market_starter/documentSend&user_token=' . $user_token . '&order_id=' . $order_id . '"
        target="_blank" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="Отправить Документ">
            Отправить Документ
        </a>
        ';
        preg_match($search_str, $output, $matches, PREG_OFFSET_CAPTURE);
        if ($matches) {
            $pos = strlen($matches[0][0]) + $matches[0][1];
            $output = substr($output, 0, $pos) . $insert_str . substr($output, $pos);
        }

        $search_str = '#<div class="pull-right">#m';
        $insert_str = '
        <a href="index.php?route=fe/api/market_starter/documentDownload&user_token=' . $user_token . '&order_id=' . $order_id . '"
        target="_blank" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="Скачать Документ">
            Скачать Документ
        </a>
        ';
        preg_match($search_str, $output, $matches, PREG_OFFSET_CAPTURE);
        if ($matches) {
            $pos = strlen($matches[0][0]) + $matches[0][1];
            $output = substr($output, 0, $pos) . $insert_str . substr($output, $pos);
        }
    }

}
