<?php
class ModelFeMarketPricesByCategory extends Model {

    public function getByUnique($price_for_id, $client_cat_id, $cat_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "product_price_by_category WHERE price_for_id = '" . (int)$price_for_id . "' AND client_category_id = '" . (int)$client_cat_id . "' AND category_id = '" . (int)$cat_id . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function add($data) {
        $prefix = DB_PREFIX;
        $price_for_id = (int)$data['price_for_id'];
        $client_category_id =  (int)$data['client_category_id'];
        $category_id = (int)$data['category_id'];
        $percent = (float)$data['percent'];
        $by_uc = $this->getByUnique($data['price_for_id'], $data['client_category_id'], $data['category_id']);

        if (!$by_uc) {
            $sql = "INSERT INTO {$prefix}product_price_by_category SET
                price_for_id = '{$price_for_id}',
                client_category_id = '{$client_category_id}',
                category_id = '{$category_id}',
                percent = '{$percent}'";
            $result = $this->db->query($sql);
            $result = $this->getByUnique($data['price_for_id'], $data['client_category_id'], $data['category_id']);
            return $result['id'];
        } else {
            $sql = "UPDATE {$prefix}product_price_by_category SET
                percent = '{$percent}'
                WHERE id = '{$by_uc['id']}'";
            $this->db->query($sql);
            return $by_uc['id'];
        }

    }

}
