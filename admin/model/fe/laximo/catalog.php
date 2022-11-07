<?php
class ModelFeLaximoCatalog extends Model {
    public function getIdByCode($code) {
        $result = $this->db->query("SELECT id FROM " . DB_PREFIX . "laximo_catalog WHERE code = '" . $this->db->escape($code) . "'");
        return $result->row ? $result->row['id'] : false;
    }

    public function add($data) {
        $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "laximo_catalog SET brand = '" . $this->db->escape($data['brand']) . "', code = '" . $this->db->escape($data['code']) . "', name = '" . $this->db->escape($data['name']) . "'");
        $id = $this->db->getLastId();
        return $id;
    }
}