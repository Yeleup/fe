<?php
class ControllerFeApiMarketProduct extends Controller {
    public function index($args) {
        if (isset($this->request->get['product_id'])) {
            $args['product_id'] = $this->request->get['product_id'];
        }

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


        $this->load->model('fe/market/crosscode');

        foreach ($result->message as $product) {
            $productByGuid = $this->model_fe_catalog_product->getProductByGuid($product->guid);

            $model = '';
            if ($product->id) {
                $model = $product->id;
            } else {
                $crosscodes = $this->model_fe_market_crosscode->getByProductId($productByGuid['product_id']);

                if (isset($crosscodes[0]['crosscode']) && !empty($crosscodes[0]['crosscode'])) {
                    $model = $crosscodes[0]['crosscode'];
                }
            }

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

                'model' => $model,
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

            $imagePath = DIR_IMAGE . 'catalog/products/'. $product->guid;
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0777, true);
            }

            // Image
            $fileGetContents = 'http://87.255.197.177:35000/api/image/'. $product->guid;

            $countImages = json_decode(@file_get_contents($fileGetContents), true);

            for ($i = 1; $i <= $countImages['count']; $i++) {
                $format = 'jpg';
                $image = 'catalog/products/'. $product->guid . '/' . $i . '.' . $format;

                if ($i == 1) {
                    $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($image) . "' WHERE product_id = '" . (int)$product_id . "'");

                    $ch = curl_init('http://87.255.197.177:35000/api/image/'. $product->guid . '/' . $format . '/' . $i);
                    $fp = fopen($imagePath . '/' . $i . '.' . $format, 'wb');
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_exec($ch);
                    curl_close($ch);
                    fclose($fp);
                } else {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $image . "'");

                    if (!$query->num_rows) {
                        $ch = curl_init('http://87.255.197.177:35000/api/image/'. $product->guid . '/' . $format . '/' . $i);
                        $fp = fopen($imagePath . '/' . $i . '.' . $format, 'wb');
                        curl_setopt($ch, CURLOPT_FILE, $fp);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_exec($ch);
                        curl_close($ch);
                        fclose($fp);

                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($image) . "', sort_order = '" . $i . "'");
                    }
                }
            }
        }

        // $this->load->controller('fe/api/market/balance');

        return true;
    }
}
