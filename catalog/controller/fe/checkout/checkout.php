<?php
class ControllerFeCheckoutCheckout extends Controller {

    public function index() {
        // Load Models
        $this->load->model('fe/util/log');
        $this->load->model('fe/customer/fe_customer');
        $this->load->model('fe/customer/address');
        $this->load->model('fe/customer/requisite');
        $this->load->model('fe/checkout/order');
        $this->load->model('fe/catalog/product');
        $this->load->model('fe/checkout/coupon');

        $this->model_fe_util_log->info('checkout', json_encode($this->request->post));

        $validation_errors = $this->validate();
        // Check for errors
        if ($validation_errors) {
            $this->response->redirect($this->url->link('fe/common/message', [
                'message' => 'Ошибка',
                'submessage' => ($validation_errors[0] ?? "Ошибка: заказ не удался.")
            ]));
        }

        // Get customer data
        $customer_id = $this->session->data['customer_id'] ?? 0;
        $fe_customer = $this->model_fe_customer_fe_customer->getByCustomerId($customer_id);


        $coupon_code = $this->model_fe_checkout_coupon->getActiveCoupon();
        $coupon = $this->model_fe_checkout_coupon->getValidCouponByCustomer($coupon_code, $customer_id);
        $coupon_discount = ($coupon ? $coupon['discount'] : 0.0) / 100;
        $coupon_price_multiplier = 1 - $coupon_discount;
        $this->model_fe_checkout_coupon->setActiveCoupon('');

        if (!$coupon && $coupon_code) {
            $this->response->redirect($this->url->link('fe/common/message', [
                'message' => 'Ошибка',
                'submessage' => ($validation_errors[0] ?? "Ошибка: Купон не является актуальной.")
            ]));
        }


        // Customer Info
        $customer_info = [
            'firstName' => $this->request->post['firstName'],
            'lastName' => $this->request->post['lastName'],
            'middleName' => ($this->request->post['middleName'] ?? ''),
            'email' => $this->request->post['email'],
            'phone' => $this->request->post['phone'],
            'company' => ($this->request->post['company'] ?? '')
        ];



        // Get Address
        $address_result = $this->model_fe_customer_address->getByIdAndCustomerId($this->request->post['address_id'] ?? 0, $customer_id);
        if ($address_result) {
            $region = $address_result['region'];
            $town = $address_result['town'];
            $street = $address_result['street'];
            $house = $address_result['house'];
            $entrance = $address_result['entrance'];
            $apartment = $address_result['apartment'];
        } else {
            $region = $this->request->post['region'] ?? '';
            $town = $this->request->post['town'] ?? '';
            $street = $this->request->post['street'] ?? '';
            $house = $this->request->post['house'] ?? '';
            $entrance = $this->request->post['entrance'] ?? '';
            $apartment = $this->request->post['apartment'] ?? '';
        }

        $address = [];
        if ($street) $address[] = $street;
        if ($house) $address[] = $house;
        if ($entrance) $address[] = $entrance;
        if ($apartment) $address[] = $apartment;

        $city = [];
        if ($region) $city[] = $region;
        if ($town) $city[] = $town;

        $delivery_option = ($this->request->post['delivery_option'] ?? 'false') === 'true' ? true : false;


        // Get Requisites
        $requisite_id = $this->request->post['requisite_id'] ?? null;
        if ($requisite_id && $customer_id) {
            $requisite = $this->model_fe_customer_requisite->getOneByIdAndCustomerId($requisite_id, $customer_id);
        }



        // Get Responsbiles
        if ($fe_customer) {
            $this->load->model('fe/market/responsible');
            $responsible = $this->model_fe_market_responsible->getById($fe_customer['responsible_id']);
        }



        // Create Order
        $cart_products = $this->cart->getProducts();
        $products = [];
        foreach ($cart_products as $cart_product) {
            $prod = $this->model_fe_catalog_product->getFullById($cart_product['product_id']);
            $product = $cart_product;
            $product['tax'] = 0;
            $product['price'] = round($prod['price_discount'] ?? $prod['price']);
            $product['total'] = $product['price'] * $product['quantity'] * $coupon_price_multiplier;
            $products[] = $product;
        }

        $order_id = $this->model_fe_checkout_order->add([
            'products' => $products,
            'firstname' => $customer_info['firstName'],
            'lastname' => $customer_info['lastName'],
            'email' => $customer_info['email'],
            'telephone' => $customer_info['phone'],
            'company' => $customer_info['company'],
            'address1' => implode(', ', $address),
            'city' => implode(' ', $city),
            'comment' => $this->request->post['comment'] ?? '',
            'custom_field' => [
                'fe' => [
                    'region' => $region,
                    'town' => $town,
                    'street' => $street,
                    'house' => $house,
                    'entrance' => $entrance,
                    'apartment' => $apartment,
                    'requisite_guid' => $requisite['guid'] ?? null,
                    'responsible_guid' => $responsible['guid'] ?? null,
                    'delivery' => $delivery_option,
                    'coupon_id' => $coupon['coupon_id'] ?? 0
                ]
            ]
        ]);

        $this->session->data['order_id'] = $order_id;

        $this->load->model('fe/market/document');
        $m_document = $this->model_fe_market_document->generateDocumentByOrderId($order_id);

        $this->session->data['fe']['m_document'] = $m_document;

        $this->model_fe_util_log->info('market_document', json_encode($m_document));
        $this->model_fe_checkout_order->updateCustomFieldById($order_id, ['fe' => ['document' => $m_document]]);

        $payment_option = $this->request->post['payment_option'];

        if ($payment_option === 'reserve') {
            $this->completeOrder([
                'order_id' => $order_id
            ]);
        }

        $data['link_home'] = $this->url->link('fe/common/home', '', true);
        $data['payment_option'] = $payment_option;

        if ($payment_option == 'kaspi') {
            $data['payment_method'] = $this->load->controller('extension/payment/kaspi');
        } else {
            $data['payment_method'] = $this->load->controller('extension/payment/jetpay');
        }

        $this->session->data['payment_method']['code'] = $payment_option;

        $data['order_id'] = $order_id;
        $data['footer'] = $this->load->controller('fe/common/footer');
        $data['header'] = $this->load->controller('fe/common/header');

        $this->response->setOutput($this->load->view('fe/checkout/checkout', $data));
    }



    public function completeOrder($args = null) {
        $this->load->model('fe/util/log');
        $this->load->model('fe/market/document');
        $this->load->model('fe/util/telegram');
        $this->load->model('fe/checkout/order');
        $this->load->model('fe/checkout/cart');
        $this->load->model('fe/checkout/order_status');
        $this->load->model('fe/checkout/coupon');
        $this->load->model('checkout/order');

        $order_id = $args['order_id'] ?? '';
        $order = $this->model_fe_checkout_order->getOrderById($order_id);

        if (!$order) return false;

        $order_guid = $order['guid'];
        $order_customer_id = $order['customer_id'];

        // Get Order Id Status Complete
        $order_status = $this->model_fe_checkout_order_status->getByName('Complete');

        $m_document = $order['custom_field']['fe']['document'] ?? [];
        $m_document_total_amount = round($m_document['message']['totalAmount']);

        unset($this->session->data['fe']['m_document']);
        if ($m_document) {
            $this->model_fe_checkout_cart->deleteByCustomerId($order_customer_id);

            $coupon_id = $order['custom_field']['fe']['coupon_id'];
            $this->model_fe_checkout_coupon->incrementCouponUsage($coupon_id, $order_customer_id);

            if (!$order_guid) {
                // Send Document
                $m_document_result = $this->model_fe_market_document->set($m_document);
                $this->model_fe_util_log->log('market_document_result', json_encode($m_document_result));
            }

            if ($m_document_result && $m_document_result->code == 200) {
                $this->model_fe_checkout_order->updateGuidById($order_id, $m_document_result->message->guid);
                $this->model_checkout_order->addOrderHistory($order_id, ($order_status['order_status_id'] ?? 1));
            } else {
                if (is_array($m_document_result)) {
                    $m_document_result_text = json_encode($m_document_result);
                } else {
                    $m_document_result_text = $m_document_result;
                }

                $err_msg = "Ошибка. Не удалось отправить документ. < $order_id | $m_document_result_text >";
                $this->model_fe_util_log->error('market_document_result', $err_msg);
            }
        }

        if (!$order_guid) {
            $order_histories = $this->model_fe_checkout_order->getOrderHistories($order_id);
            $order_statuses = [];
            foreach ($order_histories as $order_history) {
                $order_statuses[$order_history['status']] = $order_history['comment'];
            }

            $order_status_texts = [];
            if (isset($order_statuses['Processed'])) {
                $order_status_texts[] = "ОПЛАЧЕН";
            }
            if (isset($order_statuses['Pending'])) {
                $order_status_texts[] = "ОФОРМЛЕН";
            }
            if ($order_status_texts) {
                $status_text = implode(' и ', $order_status_texts);

                $message = "Заказ #{$order_id} на сумму {$m_document_total_amount} тг был {$status_text} пользователем {$m_document['message']['buyer']['firstName']} {$m_document['message']['buyer']['lastName']}.";
                $this->model_fe_util_telegram->sendNotifications($message);
            } else {
                $message = "Заказ #{$order_id} не удался";
                $this->model_fe_util_telegram->sendNotifications($message);
            }
        }
    }

    public function validate() {
        $errors = [];

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $errors[] = "Не правильный метод запроса.";
        }


        // Personal Info
        $last_name = $this->request->post['lastName'] ?? '';
        $first_name = $this->request->post['firstName'] ?? '';
        $email = $this->request->post['email'] ?? '';
        $phone = $this->request->post['phone'] ?? '';
        $registration_type = $this->request->post['registrationType' ?? ''];
        $payment_option = $this->request->post['payment_option'] ?? '';

        // Requisite Info
        $requisite_id = $this->request->post['requisite_id'] ?? '';

        // Address Info
        $address_id = $this->request->post['address_id'] ?? '';
        $region = $this->request->post['region'] ?? '';
        $town = $this->request->post['town'] ?? '';
        $street = $this->request->post['street'] ?? '';
        $house = $this->request->post['house'] ?? '';
        $entrance = $this->request->post['entrance'] ?? '';
        $apartment = $this->request->post['apartment'] ?? '';

        if (
            !($last_name &&
            $first_name &&
            $email &&
            $phone &&
            $registration_type &&
            $payment_option)
        ) {
            $errors[] = "Не установлены личные данные.";
        }


        $cart_products = $this->cart->getProducts();

        if (!$cart_products) {
            $errors[] = "Корзина пуста.";
        }


        if (
            !$address_id &&
            !(
                $region &&
                $town &&
                $street &&
                $house &&
                $apartment
            )
        ) {
            $errors[] = "Адрес не установлен.";
        }

        return $errors;
    }

}
