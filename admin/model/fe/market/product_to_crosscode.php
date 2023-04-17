<?php
class ModelFeMarketProductToCrosscode extends Model {

    protected $table = DB_PREFIX . "product_to_crosscode";

    public function addProductToCrosscode($data) {
        $this->db->query("INSERT IGNORE INTO {$this->table} SET product_id = '" . $this->db->escape($data['product_id']) . "', crosscode_id = '" . $this->db->escape($data['crosscode_id']) . "'");
        $id = $this->db->getLastId();
        return $id;
    }

    public function getByProductId($product_id) {
        $result = $this->db->query("SELECT * FROM {$this->table} WHERE product_id = '" . (int)$product_id . "'");
        return $result->rows;
    }

    public function deleteAll() {
        $sql = "DELETE FROM {$this->table}";
        $this->db->query($sql);
        return true;
    }
}