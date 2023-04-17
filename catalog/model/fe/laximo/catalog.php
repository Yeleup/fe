<?php
class ModelFeLaximoCatalog extends Model {

    public function getIdByCode($code) {
        $result = $this->db->query("SELECT id FROM " . DB_PREFIX . "laximo_catalog WHERE code = '" . $this->db->escape($code) . "'");
        return $result->row ? $result->row['id'] : false;
    }

}