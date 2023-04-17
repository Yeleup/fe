<?php
class ModelFeMarketPrice extends Model {
    public function getPrice($id) {
        $result = $this->db->query("SELECT product_price_id AS id, price, product_id, product_price_for_id FROM " . DB_PREFIX . "product_price WHERE product_price_id = '" . $id . "'");
        return $result->row;
    }

    public function priceIsUnique($product_id, $price_for_id) {
        $result = $this->db->query("SELECT COUNT(product_price_id) AS count FROM " . DB_PREFIX . "product_price WHERE product_id = '" . (int)$product_id . "' AND product_price_for_id = '" . (int)$price_for_id . "'");

        if ((int)$result->row['count'] > 0) {
            return false;
        }
        return true;
    }

    public function getByUnique($product_id, $price_for_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "product_price WHERE product_id = '" . (int)$product_id . "' AND product_price_for_id = '" . (int)$price_for_id . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function getPricesByProductId($product_id) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_price pp LEFT JOIN " . DB_PREFIX . "product_price_for ppf ON pp.product_price_for_id = ppf.product_price_for_id WHERE pp.product_id = '" . (int)$product_id . "'");
        return $result->rows;
    }

    public function addPrice($data) {
        $prefix = DB_PREFIX;
        $price = (float)$data['price'];
        $product_id = (int)$data['product_id'];
        $product_price_for_id = (int)$data['product_price_for_id'];
        $by_unique = $this->getByUnique($data['product_id'], $data['product_price_for_id']);

        if (!$by_unique) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_price SET price = '" . (float)$data['price'] . "', product_id = '" . (int)$data['product_id'] . "', product_price_for_id = '" . (int)$data['product_price_for_id'] . "'");
            $price_id = $this->db->getLastId();
            return $price_id;
        } else {
            $this->db->query("UPDATE {$prefix}product_price SET price = '$price'
                WHERE
                    product_id = '$product_id' AND
                    product_price_for_id = '$product_price_for_id'");
            return $by_unique['product_price_id'];
        }


    }
}
