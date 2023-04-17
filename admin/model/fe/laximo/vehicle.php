<?php
class ModelFeLaximoVehicle extends Model {
    public function getIdByUnique($catalog_id, $model, $year) {
        $result = $this->db->query("SELECT id FROM " . DB_PREFIX . "laximo_vehicle WHERE catalog_id = '" . (int)$catalog_id . "' AND model = '" . $this->db->escape($model) . "' AND year = '" . $this->db->escape($year) . "'");
        return $result->row ? $result->row['id'] : false;
    }

    public function getByCatalogId($catalog_id) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "laximo_vehicle WHERE catalog_id = '" . (int)$catalog_id . "'");
        return $result->rows;
    }

    public function add($data) {
        $vehicle_id = (int)$data['vehicle_id'];
        $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "laximo_vehicle SET vehicle_id = '" . $vehicle_id . "', catalog_id = '" . (int)$data['catalog_id'] . "', model = '" . $this->db->escape($data['model']) . "', year = '" . $this->db->escape($data['year']) . "'");
        $id = $this->db->getLastId();
        return $id;
    }
}
