<?php
class ControllerFePagesCartKept extends Controller {
	public function index() {
		if (!isset($this->session->data['customer_id'])) {
			$this->response->redirect($this->url->link('fe/pages/cart', '', true));
		}

		$this->config->load('fe_config');

		$this->document->setTitle("Отложенное");

		$data['link_delivery'] = $this->url->link('fe/pages/delivery', '', true);

		$this->load->model('fe/checkout/fe_cart');
		$carts = $this->model_fe_checkout_fe_cart->get();

		$this->load->model('fe/catalog/product');
		$this->load->model('fe/market/brand');
		$this->load->model('fe/market/crosscode');
		$items = [];
		foreach ($carts as $cart) {
			$product = $this->model_fe_catalog_product->getFullById($cart['product_id']);
			$item['brand'] = $product['brand_name'] ?? '';
			$item['crosscode'] = $product['crosscode'] ?? '';
			$item['name'] = $product['name'] ?? '';
			$item['price'] = $product['price'] ?? '';
			$item['price_discount'] = $product['price_discount'] ?? '';
			$item['cart_quantity'] = $cart['quantity'] ?? '';
			$item['id'] = $cart['id'] ?? '';
			$item['product_id'] = $product['product_id'] ?? '';
			$items[] = $item;
		}

		$data['items'] = $items;

		$data['cart_button_links'] = $this->load->controller('fe/includes/pages/cart_button_links',);
		$data['callback'] = $this->load->controller('fe/includes/pages/callback', [
			'link_redirect' => $this->url->link('fe/pages/cart_kept', '', true)
		]);
		$data['link_delete_fe_cart'] = $this->url->link('fe/pages/cart_kept/delete', '', true);
		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/cart_kept', $data));
	}

	public function delete() {
		$id = $this->request->post['id'];
		$this->load->model('fe/checkout/fe_cart');
		$this->model_fe_checkout_fe_cart->delete($id);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode([
			'message' => 'success'
		]));
	}
}
