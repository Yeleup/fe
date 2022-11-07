<?php
class ControllerFeApiCheckoutFeCart extends Controller {

    public function copy() {
        $err = [
            'code' => 400,
            'message' => 'Ошибка.'
        ];
        $json = [];
        $this->response->addHeader('Content-Type: application/json');

        if (isset($this->request->get['cart_id'])) {
            $cart_id = $this->request->get['cart_id'];
        } else {
            $this->response->setOutput(json_encode($err));
            return;
        }

        $carts = $this->cart->getProducts();
        $cart_ids = [];

        $this->load->model('fe/checkout/fe_cart');
        foreach ($carts as $cart) {
            $cart_ids[] = $cart['cart_id'];
        }

        $cart_id_exists = in_array($cart_id, $cart_ids);

        if ($cart_id_exists) {
            $result = $this->model_fe_checkout_fe_cart->copyByCartId($cart_id);
        } else {
            $this->response->setOutput(json_encode($err));
            return;
        }

        $json['code'] = 200;
        $json['message'] = 'Корзина успешно отложена.';

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }



    public function remove() {
        $err = [
            'code' => 400,
            'message' => 'Ошибка.'
        ];
        $json = [];
        $this->response->addHeader('Content-Type: application/json');

        if (isset($this->request->post['id'])) {
            $id = $this->request->post['id'];
        } else {
            $this->response->setOutput(json_encode($err));
            return;
        }

        $this->load->model('fe/checkout/fe_cart');
        $this->model_fe_checkout_fe_cart->delete($id);

        $json['code'] = 200;
        $json['message'] = 'Отложенный предмет успешно удален.';
        $this->response->setOutput(json_encode($json));
    }

}
