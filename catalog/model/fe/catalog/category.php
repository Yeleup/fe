<?php
class ModelFeCatalogCategory extends Model {

    public function getByProductId($product_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "product_to_category ptc
            LEFT JOIN " . DB_PREFIX . "category c ON ptc.category_id = c.category_id
            LEFT JOIN " . DB_PREFIX . "category_description cd ON c.category_id = cd.category_id
            WHERE ptc.product_id = '" . (int)$product_id . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
