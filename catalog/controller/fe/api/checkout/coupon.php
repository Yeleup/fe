<?php
class ControllerFeApiCheckoutCoupon extends Controller {

    public function setCoupon() {
        $this->load->model('fe/checkout/coupon');

        $coupon_code = $this->request->post['coupon'] ?? null;
        $this->model_fe_checkout_coupon->setActiveCoupon($coupon_code);

        $customer_id = $this->session->data['customer_id'] ?? 0;
        $coupon = $this->model_fe_checkout_coupon->getValidCouponByCustomer($coupon_code, $customer_id);
        $coupon_discount = ($coupon ? $coupon['discount'] : 0.0) / 100;
        $coupon_price_multiplier = 1 - $coupon_discount;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode([
            'is_coupon_set' => $coupon ? true : false,
            'coupon_discount' => $coupon_discount,
            'coupon_price_multiplier' => $coupon_price_multiplier
        ]));
    }

}
