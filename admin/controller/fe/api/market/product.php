<?php
class ControllerFeApiMarketProduct extends Controller {
    public function index($args) {
        $this->load->model('fe/catalog/product');
        $this->load->model('fe/catalog/category');
        $this->load->model('fe/localization/language');

        $language_id = $this->model_fe_localization_language->getCurrentId();

        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();

        if (isset($args['product_id'])) {
            $guid = $this->model_fe_catalog_product->getGuidById($args['product_id']);
            $result = $client->get('/market/product/get/' . $guid);
        } else {
            $result = $client->get('/market/product/get/list');
        }

        foreach ($result->message as $product) {
            $prod_cat_id = $this->model_fe_catalog_category->getCategoryByGuid($product->category)['category_id'] ?? null;

            $data = [
                'guid' => $product->guid,
                'product_brand_guid' => $product->brand,
                'product_description' => [
                    $language_id => [
                        'name' => $product->name,
                        'description' => '',
                        'tag' => '',
                        'meta_title' => $product->name,
                        'meta_description' => '',
                        'meta_keyword' => ''
                    ],
                ],
                'product_category' => [
                    $prod_cat_id
                ],
                'product_store' => [
                    0,
                ],

                'model' => ' ',
                'sku' => '',
                'upc' => '',
                'ean' => '',
                'jan' => '',
                'isbn' => '',
                'mpn' => '',
                'location' => '',
                'minimum' => 1,
                'subtract' => 1,
                'stock_status_id' => 7,
                'date_available' => date("Y/m/d"),
                'manufacturer_id' => 0,
                'shipping' => 1,
                'price' => 0.0,
                'points' => 0,
                'weight' => 0.0,
                'weight_class_id' => 1,
                'length' => 0.0,
                'width' => 0.0,
                'height' => 0.0,
                'length_class_id' => 0,
                'status' => $product->status,
                'tax_class_id' => 9,
                'sort_order' => 0,
            ];

            $product_id = $this->model_fe_catalog_product->add($data);

            try {
                if ($product->disable) {
                    $this->db->query("DELETE FROM `oc_product_to_category` WHERE `product_id` = '" . $product_id . "'");
                    $this->db->query("INSERT INTO `oc_product_to_category`(`product_id`, `category_id`) VALUES ('" . $product_id . "', '142')");
                } else {
                    $this->db->query("DELETE FROM `oc_product_to_category` WHERE `product_id` = '" . $product_id . "' AND `category_id` = '142'");
                }
            } catch (Exception $e) {}

            $this->load->model('fe/market/subcategory');
            if ($product->subcategory ?? false) {
                $this->model_fe_market_subcategory->addToProduct($product_id, ['name' => $product->subcategory]);
            }
        }

        // $this->load->controller('fe/api/market/balance');

        return true;
    }
}
