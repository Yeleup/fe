<?php
class ControllerFeCustomerOrder extends Controller {

    public function index() {
        $limit = 12;
        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;
        $orders_count = 0;
        $params = '';

        $this->load->model('fe/customer/order');
        $orders_count = (int)$this->model_fe_customer_order->get(true)['count'];
        $orders = $this->model_fe_customer_order->get(false, $offset, $limit);

        $this->load->model('customer/customer');
        $this->load->model('catalog/product');
        $data['orders'] = [];
        foreach ($orders as $order) {
            $customer = $this->model_customer_customer->getCustomer($order['customer_id']);
            $products = [];
            $cart_items = json_decode($order['cart_info'], true);

            foreach ($cart_items as $cart_item) {
                $product = $this->model_catalog_product->getProduct($cart_item['product_id']);
                $product['amount_ordered'] = $cart_item['quantity'];
                $products[] = $product;
            }

            if ($customer) {
                $o['customer'] = $customer['firstname'] . ' ' . $customer['lastname'];
            } else {
                $o['customer'] = '-';
            }

            $order_address = array_filter(json_decode($order['address'], true), function ($v) {
                return (boolean)$v;
            });

            if ($order['customer_info']) {
                $customer_info = array_filter(json_decode($order['customer_info'], true), function ($v) {
                    return (boolean)$v;
                });
            } else {
                $customer_info = [];
            }

            $o['id'] = $order['id'];
            $o['address'] = $order_address ? implode(', ', $order_address) : '';
            $o['customer_info'] = $customer_info ? implode(', ', $customer_info) : '-';
            $o['status'] = $this->model_fe_customer_order->getStatusName($order['status_id']);
            $o['products'] = $products;
            $o['created_at'] = $order['created_at'];
            $data['orders'][] = $o;
        }

        $pagination = new Pagination();
        $pagination->total = $orders_count;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('fe/customer/order', 'user_token=' . $this->session->data['user_token'] . $params . '&page={page}', true);
        $data['pagination'] = $pagination->render();

        $data['lang_heading_title'] = 'Заказы';

		$this->document->setTitle($data['lang_heading_title']);

		$data['user_token'] = $this->session->data['user_token'];

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('fe/customer/order', $data));
    }

}
