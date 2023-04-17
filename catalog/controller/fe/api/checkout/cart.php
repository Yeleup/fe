<?php
class ControllerFeApiCheckoutCart extends Controller {

    public function getProductCount() {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode([
            'code' => 200,
            'productCount' => $this->cart->countProducts()
        ]));
    }


    public function getProducts() {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode([
            'code' => 200,
            'products' => $this->cart->getProducts()
        ]));
    }

}
