<?php
class ModelFeCheckoutOrder extends Model {

    public function updateGuidById($id, $guid) {
        $prefix = DB_PREFIX;
        $id = (int)$id;
        $guid = $this->db->escape($guid);

        $sql = "UPDATE {$prefix}order
            SET `guid` = '$guid'
            WHERE `order_id` = '$id'";
        $this->db->query($sql);
        return $id;
    }

    public function getOrderById($id) {
        $prefix = DB_PREFIX;
        $id = (int)$id;
        $sql = "SELECT * FROM `{$prefix}order`
            WHERE `order_id` = {$id}";
        $result = $this->db->query($sql);
        $order = $result->row;
        if ($order) {
            $order['custom_field'] = json_decode($order['custom_field'], true);
        }
        return $order;
    }

    public function get() {
        $this->load->model('account/order');
        $this->load->model('fe/catalog/product');

        $orders = $this->model_account_order->getOrders();
        foreach ($orders as $i => $order) {
            $order_products = $this->model_account_order->getOrderProducts($order['order_id']);
            $op = [];
            foreach ($order_products as $order_product) {
                $order_product['product'] = $this->model_fe_catalog_product->getFullById($order_product['product_id']);
                $op[] = $order_product;
            }
            $order['order_products'] = $op;
            $orders[$i] = $order;
        }
        return $orders;
    }

    public function updateCustomFieldById($order_id, $custom_field) {
        $prefix = DB_PREFIX;
        $order_id = (int)$order_id;

        $this->load->model('checkout/order');
        $order = $this->model_checkout_order->getOrder($order_id);

        if (!$order) {
            return false;
        }

        $order_custom_field = $order['custom_field'];
        $custom_field['fe'] = array_merge($custom_field['fe'], $order_custom_field['fe']);
        $custom_field = $this->db->escape(json_encode($custom_field));

        $sql = "UPDATE {$prefix}order SET
            `custom_field` = '{$custom_field}'
            WHERE `order_id` = '{$order_id}'";
        $this->db->query($sql);
        return $order_id;
    }

    public function getOrderHistories($order_id) {
        $query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added DESC");
        return $query->rows;
    }

    public function add($data) {
        $order_store_id = $this->config->get('config_store_id');

        if ($order_store_id) {
            $order_store_url = $this->config->get('config_url');
        } else {
            if ($this->request->server['HTTPS']) {
                $order_store_url = HTTPS_SERVER;
            } else {
                $order_store_url = HTTP_SERVER;
            }
        }

        $this->load->model('account/customer');
        if ($this->customer->isLogged()) {
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        } else {
            $customer_info = [];
        }

        $this->load->model('fe/localization/language');
        $language_id = $this->model_fe_localization_language->getCurrentId();

        $currency_code = "KZT";
        $this->load->model('localisation/currency');
        $currency = $this->model_localisation_currency->getCurrencyByCode($currency_code);

        $this->load->model('fe/checkout/order_status');
        $order_status = $this->model_fe_checkout_order_status->getByName('Pending');
        // var_dump($order_status);

        $order_total = 0.0;
        foreach ($data['products'] as $product) {
            $order_total += $product['total'];
        }

        $order_delivery = $data['custom_field']['fe']['delivery'] ?? false;
        if ($order_delivery) {
            $this->load->model('fe/util/util');
            $order_total += (int)($this->model_fe_util_util->getByName('delivery_price')['int_val'] ?? 1000);
        }

        $this->load->model('checkout/order');
        $order_id = $this->model_checkout_order->addOrder([
            'invoice_prefix' => $this->config->get('config_invoice_prefix'),
            'store_id' => $order_store_id,
            'store_name' => $this->config->get('config_name'),
            'store_url' => $order_store_url,
            'customer_id' => $customer_info['customer_id'] ?? 0,
            'customer_group_id' => $customer_info['customer_group_id'] ?? 1,
            'firstname' => $customer_info['firstname'] ?? $data['firstname'],
            'lastname' => $customer_info['lastname'] ?? $data['lastname'],
            'email' => $customer_info['email'] ?? $data['email'],
            'telephone' => $customer_info['telephone'] ?? $data['telephone'],
            'fax' => '',
            'custom_field' => $data['custom_field'] ?? [],
            'payment_firstname' => $data['firstname'] ?? '',
            'payment_lastname' => $data['lastname'] ?? '',
            'payment_company' => $data['company'] ?? '',
            'payment_address_1' => $data['address1'] ?? '',
            'payment_address_2' => '',
            'payment_city' => $data['city'] ?? '',
            'payment_postcode' => '',
            'payment_country' => 'Kazakhstan',
            'payment_country_id' => '',
            'payment_zone' => '',
            'payment_zone_id' => '',
            'payment_address_format' => '',
            'payment_custom_field' => '',
            'payment_method' => '',
            'payment_code' => '',
            'shipping_firstname' => '',
            'shipping_lastname' => '',
            'shipping_company' => '',
            'shipping_address_1' => '',
            'shipping_address_2' => '',
            'shipping_city' => '',
            'shipping_postcode' => '',
            'shipping_country' => '',
            'shipping_country_id' => '',
            'shipping_zone' => '',
            'shipping_zone_id' => '',
            'shipping_address_format' => '',
            'shipping_custom_field' => '',
            'shipping_method' => '',
            'shipping_code' => '',
            'comment' => $data['comment'] ?? '',
            'total' => $order_total,
            'affiliate_id' => '',
            'commission' => '',
            'marketing_id' => '',
            'tracking' => '',
            'language_id' => ($language_id ?? 1),
            'currency_id' => $currency['currency_id'] ?? 0,
            'currency_code' => $currency_code,
            'currency_value' => $currency['value'] ?? 0,
            'ip' => $this->request->server['REMOTE_ADDR'],
            'forwarded_ip' => '',
            'user_agent' => $this->request->server['HTTP_USER_AGENT'] ?? '',
            'accept_language' => $this->request->server['HTTP_ACCEPT_LANGUAGE'] ?? '',
            'products' => $data['products']
        ]);
        $this->model_checkout_order->addOrderHistory($order_id, ($order_status['order_status_id'] ?? 1));
        return $order_id;
    }

}
