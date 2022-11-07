<?php
class ModelFeLaximoCleanup extends Model {

    public function clean() {
        $this->db->query("DELETE lun FROM " . DB_PREFIX . "laximo_unit_name lun LEFT JOIN " . DB_PREFIX . "laximo_product_to_unit_name lptun on lun.id = lptun.unit_name_id left join " . DB_PREFIX . "product p on lptun.product_id = p.product_id where lptun.id is null");
        $this->db->query("DELETE lv FROM " . DB_PREFIX . "laximo_vehicle lv LEFT JOIN " . DB_PREFIX . "laximo_unit_name lun on lv.id = lun.vehicle_id where lun.id is null");
        $this->db->query("DELETE lc FROM " . DB_PREFIX . "laximo_catalog lc LEFT JOIN " . DB_PREFIX . "laximo_vehicle lv on lc.id = lv.catalog_id where lv.id is null");
        return true;
    }

}