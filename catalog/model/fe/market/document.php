<?php
class ModelFeMarketDocument extends Model {

    public function set($data) {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->post('/market/document/set', $data);
        return $result;
    }

    public function generateDocumentByOrderId($order_id) {
        $this->load->model('checkout/order');
        $order = $this->model_checkout_order->getOrder($order_id);

        if (!$order) {
            return [];
        }



        $order_products = $this->model_checkout_order->getOrderProducts($order['order_id']);
        $products = [];
        foreach ($order_products as $order_product) {
            $product_full = $this->model_fe_catalog_product->getFullById($order_product['product_id']);

            $product = [];
            $product['product'] = $product_full['guid'];
            $product['count'] = $order_product['quantity'];
            $product['price'] = $order_product['price'];
            $products[] = $product;
        }



        $this->load->model('fe/customer/fe_customer');
        $fe_customer = $this->model_fe_customer_fe_customer->getByCustomerId($order['customer_id']);

        if ($fe_customer) {
            // Customer Type
            $this->load->model('fe/customer/customer_type');
            $customer_type = $this->model_fe_customer_customer_type->getById($fe_customer['customer_type_id']);
        }
        $buyer_type = $customer_type['name'] ?? 'personal';

        $date_added = date(DATE_ISO8601, strtotime($order['date_added']));

        $delivery = $order['custom_field']['fe']['delivery'] ?? false;

        $document = [
            'code' => 200,
            'message' => [
                'requisite_guid' => $order['custom_field']['fe']['requisite_guid'] ?? '',
                'responsible' => $order['custom_field']['fe']['responsible_guid'] ?? '',
                'delivery' => $delivery,
                'guid' => $order['guid'] ?? '',
                'date' => $date_added,
                'number' => $order['order_id'],
                'paid' => $order['total'],
                'status' => 'open',
                'totalAmount' => $order['total'],
                'discount' => 0,
                'amountWithDiscount' => $order['total'],
                'buyer' => [
                    'type' => $buyer_type,
                    'firstName' => $order['firstname'],
                    'lastName' => $order['lastname'],
                    'middleName' => '',
                    'contacts' => [
                        'emails' => [$order['email']],
                        'phoneNumbers' => [$order['telephone']]
                    ]
                ],
                'deliveryAddress' => [
                    'city' => $order['custom_field']['fe']['town'] ?? '',
                    'house' => $order['custom_field']['fe']['house'] ?? '',
                    'street' => $order['custom_field']['fe']['street'] ?? '',
                    'flat' => $order['custom_field']['fe']['apartment'] ?? ''
                ],
                'comment' => $order['comment'],
                'products' => $products
            ]
        ];

        return $document;
    }

}
