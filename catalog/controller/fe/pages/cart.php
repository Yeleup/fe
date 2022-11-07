<?php
class ControllerFePagesCart extends Controller {
	public function index() {
		$this->config->load('fe_config');
		$this->load->model('fe/catalog/product');

		$this->document->setTitle("Корзина");

		$products = $this->cart->getProducts();

		$data['products'] = [];
		$data['products_count'] = sizeof($products);
		$data['customer_id'] = $this->session->data['customer_id'] ?? null;

		$this->load->model('fe/customer/fe_customer');
		$fe_customer = $this->model_fe_customer_fe_customer->getByCustomerId($data['customer_id']);

		$this->load->model('fe/customer/customer_type');
		$customer_type = $this->model_fe_customer_customer_type->getById($fe_customer['customer_type_id'] ?? '');
		$data['customer_type'] = $customer_type;

		$this->load->model('fe/checkout/fe_cart');

		foreach ($products as $product) {
			$product['cart_quantity'] = $product['quantity'];
			$result_prod = $this->model_fe_catalog_product->getFullById($product['product_id']);
			$product = array_merge($product, $result_prod);
			$data['products'][] = $product;
		}

		$data['link_checkout'] = $this->url->link('fe/pages/checkout', '', true);
		$data['link_coupon_api'] = $this->url->link('fe/api/checkout/coupon/setCoupon', '', true);

		$data['cart_button_links'] = $this->load->controller('fe/includes/pages/cart_button_links', [
			'cart_count' => sizeof($products),
			'cart_kept_count' => (int)$this->model_fe_checkout_fe_cart->get(true)['count'],
		]);
		$data['callback'] = $this->load->controller('fe/includes/pages/callback', [
			'link_redirect' => $this->url->link('fe/pages/cart', '', true)
		]);
		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/cart', $data));
	}
}
