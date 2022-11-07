<?php
class ModelFeLaximoUnit extends Model {
    public function getIdByUnitId($unit_id) {
        $result = $this->db->query("SELECT id FROM " . DB_PREFIX . "laximo_unit WHERE unit_id = '" . (int)$unit_id . "'");
        return $result->row ? $result->row['id'] : false;
    }

    public function add($data) {
        $this->load->model('fe/laximo/unit_name');
        $this->model_fe_laximo_unit_name->add($data);
        $unit_name_id = $this->model_fe_laximo_unit_name->getIdByName($data['name']);
        if ($unit_name_id) {
            $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "laximo_unit SET unit_id = '" . (int)$data['unit_id'] . "', unit_name_id = '" . (int)$unit_name_id . "'");
        }
        $id = $this->getIdByUnitId($data['unit_id']);
        return $id;
    }
}