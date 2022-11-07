<?php
// Deprecated
class ControllerFeApiMarketDocument extends Controller {
    public function send() {
        $order_id = $this->request->get['order_id'] ?? 0;

        if (!$order_id) {
            return ['error' => 'order_id is not set.'];
        }

        $this->load->model('sale/order');
        $order = $this->model_sale_order->getOrder($order_id);

        $document = $order['custom_field']['fe']['document'] ?? [];

        $this->load->model('fe/market/document');
        $document_result = $this->model_fe_market_document->set($document);

        if ($document_result->code == '200') {
            $this->load->model('fe/checkout/order');
            $this->model_fe_checkout_order->updateGuidById($order_id, $document_result->message->guid);

            $this->load->model('fe/checkout/order_status');
            $order_status = $this->model_fe_checkout_order_status->getByName('Complete');

            $this->model_fe_checkout_order->addOrderHistory($order_id, ($order_status['order_status_id'] ?? 1));
        }

        return $document_result;
    }

    public function download() {
        $order_id = $this->request->get['order_id'] ?? 0;

        if (!$order_id) {
            return ['error' => 'order_id is not set.'];
        }

        $this->load->model('sale/order');
        $order = $this->model_sale_order->getOrder($order_id);

        $document = $order['custom_field']['fe']['document'] ?? [];
        return $document;
    }
}
