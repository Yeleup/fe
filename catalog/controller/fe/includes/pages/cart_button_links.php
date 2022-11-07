<?php
class ControllerFeIncludesPagesCartButtonLinks extends Controller {
	public function index($args) {
        $data = [];
        $data['link_cart'] = $this->url->link('fe/pages/cart', '', true);
        $data['link_cart_kept'] = $this->url->link('fe/pages/cart_kept', '', true);

        if (isset($args['cart_count'])) {
            $data['cart_count'] = $args['cart_count'];
        } else {
            $data['cart_count'] = sizeof($this->cart->getProducts());
        }

        $data['customer_id'] = $this->session->data['customer_id'] ?? null;

        if (isset($args['cart_kept_count'])) {
            $data['cart_kept_count'] = $args['cart_kept_count'];
        } else {
            $this->load->model('fe/checkout/fe_cart');
            $data['cart_kept_count'] = (int)$this->model_fe_checkout_fe_cart->get(true)['count'];
        }

		return $this->load->view('fe/includes/pages/cart_button_links', $data);
	}
}
