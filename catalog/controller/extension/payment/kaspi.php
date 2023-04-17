<?php
require_once DIR_SYSTEM . 'library/phpqrcode/qrlib.php';


class ControllerExtensionPaymentKaspi extends Controller {
	public function index() {
        $data['src'] = 'qr_code2.png';
        //print_r('https://kaspi.kz/pay/Company?service_id=3486&4973=11111111111&amount=100');die;
        QRcode::png('https://kaspi.kz/pay/Company?service_id=3486&4973=11111111111&amount=100', $data['src']);

        return $this->load->view('extension/payment/kaspi', $data);
    }

	public function confirm() {
		$json = array();
		
		if (isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'kaspi') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 5);
		
			$json['redirect'] = $this->url->link('checkout/success');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
}
