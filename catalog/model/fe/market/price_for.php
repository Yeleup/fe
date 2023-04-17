<?php
class ModelFeMarketPriceFor extends Model {

    public function getByProductId($product_id) {
        $sort_order = '99607e00-f93a-11db-b066-00142ab1de64'; // Розничная
        $sql = "SELECT * FROM " . DB_PREFIX . "product_price pp
            LEFT JOIN " . DB_PREFIX . "product_price_for ppf
                ON pp.product_price_for_id = ppf.product_price_for_id
            WHERE pp.product_id = '" . (int)$product_id . "'
            GROUP BY FIELD(ppf.guid, '" . $this->db->escape($sort_order) . "') DESC";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function getByUniques($product_id, $category_id, $client_category_id) {
        $prefix = DB_PREFIX;
        $product_id = (int)$product_id;
        $category_id = (int)$category_id;
        $client_category_id = (int)$client_category_id;
        $sql = "SELECT * FROM {$prefix}product_price pp
            JOIN {$prefix}product_price_for ppf
            	ON pp.product_price_for_id = ppf.product_price_for_id
            JOIN {$prefix}product_price_by_category pbc
            	ON pbc.price_for_id = ppf.product_price_for_id
            WHERE
            	pp.product_id = '$product_id'
            AND pbc.client_category_id = '$client_category_id'
            AND pbc.category_id = '$category_id' ORDER BY pp.product_price_id DESC";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
