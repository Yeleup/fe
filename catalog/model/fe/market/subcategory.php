<?php
class ModelFeMarketSubcategory extends Model {

    public function getAll() {
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM {$prefix}product_fe_subcategory";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function getOneById($id) {
        $prefix = DB_PREFIX;
        $id = (int)$id;
        $sql = "SELECT * FROM {$prefix}product_fe_subcategory WHERE id = '$id' LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function getOneByProductId($product_id) {
        $product_id = (int)$product_id;
        $prefix = DB_PREFIX;
        $sql = "SELECT s.* FROM ${prefix}product_to_fe_subcategory sb
            JOIN ${prefix}product_fe_subcategory s ON sb.subcategory_id = s.id
            WHERE sb.product_id = '$product_id'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
