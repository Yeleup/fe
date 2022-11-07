<?php
class ModelFeLaximoVehicle extends Model {

    public function getById($id) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "laximo_vehicle lv LEFT JOIN " . DB_PREFIX . "laximo_catalog lc ON lv.catalog_id = lc.id WHERE lv.id = '" . (int)$id . "'");
        return $result->row;
    }

    public function getByCatalogIdAndModel($catalog_id, $model) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "laximo_vehicle WHERE catalog_id = '" . (int)$catalog_id . "' AND model = '" . $this->db->escape($model) . "'");
        return $result->rows;
    }

    public function getModelsByCatalogId($catalog_id) {
        $result = $this->db->query("SELECT DISTINCT model FROM " . DB_PREFIX . "laximo_vehicle WHERE catalog_id = '" . (int)$catalog_id . "'");
        return $result->rows;
    }

}
