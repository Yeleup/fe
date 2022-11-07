<?php
class ControllerFeApiMarketInApiOrder extends Controller {

    public function update_status() {
        $error = [];

        if (!$error && !isset($this->session->data['api_id'])) {
            $error = [
                'code' => 401,
                'message' => 'Unauthorized.'
            ];
        }

        $order_id = $this->request->post['order_id'] ?? false;
        $status = $this->request->post['status'] ?? false;

        if (!$error && (!$order_id || !$status)) {
            $error = [
                'code' => 400,
                'message' => 'Bad request.'
            ];
        }

        if ($error) {
            $json = $error;
        } else {

            $this->load->model('fe/checkout/fe_order');
            $result = $this->model_fe_checkout_fe_order->updateStatusById($order_id, $status);

            if (!$result) {
                $json = [
                    'code' => 400,
                    'message' => 'Order status was not updated.'
                ];
            } else {
                $json = [
                    'code' => 200,
                    'message' => 'Order status updated.'
                ];
            }

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
