<?php
class ModelFeCatalogProductImage extends Model {

    protected $table = DB_PREFIX . 'product_image';

    public function getByProductId($product_id) {
        $product_id = (int)$product_id;
        $sql = "SELECT * FROM {$this->table}
            WHERE
            `product_id` = '{$product_id}'";
        $result = $this->db->query($sql);
        return $result->rows;
    }

}
