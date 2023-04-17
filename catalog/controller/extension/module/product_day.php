<?php
class ControllerExtensionModuleProductDay extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/product_day');

		$this->load->model('catalog/product');

        $this->load->model('fe/catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 1;
		}

        $specialPercent = $setting['width'];
        $setting['width'] = 200;

        $data['name'] = $setting['name'];

		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

                $product = $this->model_fe_catalog_product->getFullById($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($product['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $price = false;
                    }

					if (!is_null($product_info['special']) && (float)$product_info['special'] >= 0) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						$tax_price = (float)$product_info['special'];
					} else {
						$special = false;
						$tax_price = (float)$product_info['price'];
					}

                    if ($specialPercent) {
                        $this->load->model('account/customer');
                        $this->load->model('fe/customer/fe_customer');
                        $this->load->model('fe/customer/reg_type');

                        $customer_id = $this->session->data['customer_id'] ?? null;

                        if ($customer_id) {
                            $customer_data = $this->model_fe_customer_fe_customer->getByCustomerId($customer_id);
                            $customer_reg_type = $this->model_fe_customer_reg_type->getById($customer_data['reg_type'] ?? 0);
                            $customer_reg_type_name = $customer_reg_type['name'] ?? '';
                            if ($customer_reg_type_name == 'retail') {
                                $special = $this->currency->format($this->tax->calculate($product['price'] - ($product['price'] * ($setting['width']/100)), $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                            }
                        }
                    }
		
					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format($tax_price, $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'href'        => $this->url->link('fe/pages/details', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		if ($data['products']) {
			return $this->load->view('extension/module/product_day', $data);
		}
	}
}