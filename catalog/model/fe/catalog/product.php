<?php
class ModelFeCatalogProduct extends Model {

    public function getByGuid($guid) {
        $prefix = DB_PREFIX;
        $guid = $this->db->escape($guid);
        
		/*$sql = "SELECT * FROM ${prefix}product
            WHERE guid = '$guid' LIMIT 1";*/
       
		$sql = "SELECT * FROM ${prefix}product
            WHERE guid = '$guid' AND p.status = 1 LIMIT 1";
		
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function get($offset = 0, $limit = 20) {
        /*$sql = "SELECT * FROM " . DB_PREFIX . "product
            WHERE guid IS NOT NULL
            LIMIT " . (int)$offset . ", " . (int)$limit . "";*/
     
		$sql = "SELECT * FROM " . DB_PREFIX . "product
            WHERE guid IS NOT NULL AND p.status = 1
            LIMIT " . (int)$offset . ", " . (int)$limit . "";
        
		$result = $this->db->query($sql);
        return $result->rows;
    }

    private function getById($id) {
        /*$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id WHERE p.product_id = '" . (int)$id . "'");*/
        
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id WHERE p.product_id = '" . (int)$id . "' AND p.status = 1");
		
        return $result->row;
    }

    private function getCrossCodesById($id) {
		$result = $this->db->query("SELECT pc.crosscode FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id LEFT JOIN " . DB_PREFIX . "product_to_crosscode ptc ON p.product_id = ptc.product_id LEFT JOIN " . DB_PREFIX . "product_crosscode pc ON ptc.crosscode_id = pc.product_crosscode_id WHERE p.product_id = '" . (int)$id . "'");
        
        $array = array();

        foreach($result->rows as $row){ 
            array_push($array, $row['crosscode']);
        }

        return $array ;
    }

    public function getFullById($id) {
        $this->load->model('fe/market/price_for');
        $this->load->model('fe/market/crosscode');
        $this->load->model('fe/market/brand');
        $this->load->model('fe/catalog/category');
        $this->load->model('fe/catalog/product_image');
        $this->load->model('catalog/product');
        $this->load->model('fe/market/price_by_category');
        $this->load->model('fe/market/client_category');

        $product = $this->getById($id);
        $category = $this->model_fe_catalog_category->getByProductId($id);

        $customer_id = $this->session->data['customer_id'] ?? 0;
        $client_category = $this->model_fe_market_client_category->getByCustomerId($customer_id);
        if (!$client_category) {
            $client_category = $this->model_fe_market_client_category->getWhereRetail();
        }
        $client_category_id = $client_category ? $client_category['id'] : '';

        $price_for = $this->model_fe_market_price_for->getByUniques($id, $category['category_id'] ?? '', $client_category_id);
        if (!$price_for) { // return if price not found
            return false;
        }
		
        if (!isset($product['guid'])) { 
            return false;
        }
		
		$crosscodes = $this->getCrossCodesById($id);
        if ($crosscodes) {
            $product['crosscodes'] = $crosscodes;
        }
        
		$crosscode = $this->model_fe_market_crosscode->getOneCrosscodeByProductId($id);
        $brand = $this->model_fe_market_brand->getByProductGuid($product['guid']);
        $percent = $this->model_fe_market_price_by_category->getPercentByUnique(
            $price_for['product_price_for_id'],
            $category['category_id'],
            $client_category_id
        );

        $product_images = $this->model_catalog_product->getProductImages($id);
        if ($product_images) {
            $product['images'] = $product_images;
        }

        $product['price'] = $price_for['price'] * $percent;
        if ($crosscode) {
            $product['crosscode'] = $crosscode['crosscode'];
        }
        $product['brand_name'] = $brand['name'];
        if ($percent !== false && $percent != 1) {
            $product['price_discount'] = $product['price'];
        }

        /*$product_images = $this->model_fe_catalog_product_image->getByProductId($product['product_id']);
        if ($product_images) {
            $product['images'] = $product_images;
        }*/

        return $product;
    }



    public function getBySubcategoryId($subcategory_id, $count = false, $offset = 0, $limit = 8) {
        $offset = (int)$offset;
        $limit = (int)$limit;
        $subcategory_id = (int)$subcategory_id;
        $prefix = DB_PREFIX;

        $sql_select = $count ? "COUNT(p.product_id) AS count" : "p.*";
        $sql_limits = $count ? "" : "LIMIT $offset, $limit";
        $sql = "SELECT $sql_select FROM ${prefix}product p
            JOIN ${prefix}product_to_fe_subcategory sb ON p.product_id = sb.product_id
            JOIN ${prefix}product_fe_subcategory s ON sb.subcategory_id = s.id
            WHERE s.id = '$subcategory_id' AND p.status = 1
            $sql_limits";
        $result = $this->db->query($sql);
        return $count ? $result->row : $result->rows;
    }



    public function getByNameSearch($name, $count = false, $offset = null, $limit = 8) {
        $name = $this->db->escape($name);
        $words = explode(' ', $name);
        $words = array_slice($words, 0, 4); // Only first 4 words

        $sql_select = $count ? " COUNT(*) AS count " : " * ";
        
		$sql = "SELECT " . $sql_select . " FROM " . DB_PREFIX . "product p";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_crosscode ptc ON ptc.product_id = p.product_id";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_crosscode pc ON ptc.crosscode_id = pc.product_crosscode_id";
        $sql .= " WHERE p.guid IS NOT NULL AND p.status = 1";

        if ($words) {
            $sql .= " AND ( " . implode(" AND ", array_map(function($e) {return "pd.name LIKE '%" . $e . "%' OR pc.crosscode LIKE '%" . $e . "%'";}, $words)) . " )";
        }

        // Set Limits
        $sql_limit = " LIMIT %s, %s ";
        if ($offset === null) {
            $offset = 0;
        }
        $sql_limit = sprintf(
            $sql_limit,
            (int)$offset,
            (int)$limit
        );

        // Add limit if not counting
        if (!$count) {
            $sql .= $sql_limit;
        }

        $result = $this->db->query($sql);

        // Return one row if counting
        if ($count) {
            return $result->row;
        } else {
            return $result->rows;
        }
    }



    public function getByIds($ids) {
        if (!$ids) {
            return null;
        }

        $l = '(';
        foreach ($ids as $id) {
            $l .= '"' . (int)$id . '",';
        }
        $l = substr($l, 0, strlen($l) - 1) . ')';

        /*$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id LEFT JOIN " . DB_PREFIX . "product_to_crosscode ptc ON p.product_id = ptc.product_id LEFT JOIN " . DB_PREFIX . "product_crosscode pc ON ptc.crosscode_id = pc.product_crosscode_id WHERE p.product_id in " . $l . "");
        return $result->rows;
    }*/
       
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id LEFT JOIN " . DB_PREFIX . "product_to_crosscode ptc ON p.product_id = ptc.product_id LEFT JOIN " . DB_PREFIX . "product_crosscode pc ON ptc.crosscode_id = pc.product_crosscode_id WHERE p.product_id in " . $l . " AND p.status = 1");
        return $result->rows;
    }

    public function getByCrosscode($crosscode) {
        $this->load->model('fe/util/crosscode');
        $crosscode = $this->model_fe_util_crosscode->normalize($crosscode);
        /*$sql = "SELECT * FROM " . DB_PREFIX . "product p
        LEFT JOIN " . DB_PREFIX . "product_to_crosscode ptc ON p.product_id = ptc.product_id
        LEFT JOIN " . DB_PREFIX . "product_crosscode pc ON ptc.crosscode_id = pc.product_crosscode_id
        WHERE pc.crosscode = '" . $this->db->escape($crosscode) . "' AND p.status = 1";*/

		$sql = "SELECT * FROM " . DB_PREFIX . "product p
        LEFT JOIN " . DB_PREFIX . "product_to_crosscode ptc ON p.product_id = ptc.product_id
        LEFT JOIN " . DB_PREFIX . "product_crosscode pc ON ptc.crosscode_id = pc.product_crosscode_id
        WHERE pc.crosscode Like '%" . $this->db->escape($crosscode) . "%' AND p.status = 1";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function getProductsByOem($oem) {
        $this->load->model('fe/market/crosscode');
        $products = $this->getByCrosscode($oem);
        $crosscodes = [];

        foreach ($products as $product) {
            $result_crosscodes = $this->model_fe_market_crosscode->getCrosscodesByProductId($product['product_id']);
            foreach ($result_crosscodes as $crosscode) {
                $crosscodes[$crosscode['crosscode']] = 1;
            }
        }

        $result_product_ids = [];
        foreach ($crosscodes as $crosscode => $_) {
            $products = $this->getByCrosscode($crosscode);
            foreach ($products as $product) {
                $result_product_ids[$product['product_id']] = 1;
            }
        }

        $products = [];
        foreach ($result_product_ids as $product_id => $_) {
            $products[] = $this->getById($product_id);
        }

        return $products;
    }
}
