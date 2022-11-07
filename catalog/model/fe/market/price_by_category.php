<?php
class ModelFeMarketPriceByCategory extends Model {

    public function getPercentByUnique($price_for_id, $category_id, $client_category_id = null) {
        $this->load->model('fe/market/client_category');

        $sql = "SELECT (1 - percent / 100) AS percent FROM " . DB_PREFIX . "product_price_by_category
            WHERE price_for_id = '" . (int)$price_for_id . "'
            AND client_category_id = '" . (int)$client_category_id . "'
            AND category_id = '" . (int)$category_id . "'";
        $result = $this->db->query($sql);
        return $result->row ? $result->row['percent'] : false;
    }

}
