<?php
class ModelFeMarketPrice extends Model {

    public function getIdByGuid($guid = null) {
        if ($guid === null) {
            $guid = '99607e00-f93a-11db-b066-00142ab1de64';
        }

        $sql = "SELECT product_price_for_id AS id FROM " . DB_PREFIX . "product_price_for WHERE guid = '" . $this->db->escape($guid) . "' LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row ? $result->row['id'] : false;
    }

    public function getPriceByProductId($product_id) {
        $price_for_guid = '74f56880-7162-11e5-93e1-a0b3cce0782b';

        $sql = "SELECT p.product_id, pp.*, ppf.* FROM " . DB_PREFIX . "product p
            LEFT JOIN " . DB_PREFIX . "product_price pp ON p.product_id = pp.product_id
            LEFT JOIN " . DB_PREFIX . "product_price_for ppf ON pp.product_price_for_id = ppf.product_price_for_id
            WHERE
            p.product_id = '" . (int)$product_id . "' AND
            (ppf.guid = '" . $this->db->escape($price_for_guid) . "' OR
            ppf.guid = '99607e00-f93a-11db-b066-00142ab1de64')
            GROUP BY p.product_id";
        $result = $this->db->query($sql);

        return $result->row;
    }

    public function getDiscount($price_for_id, $client_category_id, $category_id) {
        $sql = "SELECT percent FROM " . DB_PREFIX . "product_price_by_category ppbc
            WHERE price_for_id = '" . (int)$price_for_id . "'
            AND client_category_id = '" . (int)$client_category_id . "'
            AND category_id = '" . (int)$category_id . "'";
        $result = $this->db->query($sql);
        return $result->row ? $result->row : false;
    }

}
