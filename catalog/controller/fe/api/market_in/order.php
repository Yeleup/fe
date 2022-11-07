<?php
class ControllerFeApiMarketInOrder extends Controller {

    public function update_status() {
        $error = [];

        if (!$error && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $error = [
                'code' => 405,
                'message' => 'Request method must be POST.'
            ];
        }

        if (!$error && !$this->load->controller('fe/api/market_in/auth_validator/validate')) {
            $error = [
                'code' => 401,
                'message' => 'Unauthorized.'
            ];
        }

        $request_post = json_decode(file_get_contents('php://input'), true);
        $request_post = $this->request->clean($request_post);

        $order_id = $request_post['order_id'] ?? false;
        $status = $request_post['status'] ?? false;

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
