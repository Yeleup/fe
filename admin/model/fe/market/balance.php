<?php
class ModelFeMarketBalance extends Model {
    public function addBalance($data) {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$data['quantity'] . "' WHERE guid = '" . $this->db->escape($data['guid']) . "'");
        return true;
    }
}