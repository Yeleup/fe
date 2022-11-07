<?php
class ModelFeLaximoUnitName extends Model {
    public function getIdByName($name) {
        $result = $this->db->query("SELECT id FROM " . DB_PREFIX . "laximo_unit_name WHERE name = '" . $this->db->escape($name) . "'");
        return $result->row ? $result->row['id'] : false;
    }

    public function add($data) {
        $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "laximo_unit_name SET vehicle_id = '" . (int)$data['vehicle_id'] . "', name = '" . $this->db->escape($data['name']) . "'");
        $id = $this->getIdByName($data['name']);
        $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "laximo_product_to_unit_name SET product_id = '" . (int)$data['product_id'] . "', unit_name_id = '" . (int)$id . "'");
        return $id;
    }
}