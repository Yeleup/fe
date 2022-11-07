<?php
class ModelFeLaximoLaximo extends Model {

    public function getCatalogs() {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "laximo_catalog");
        return $result->rows;
    }

    public function getVehicles($catalog_id) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "laximo_vehicle WHERE catalog_id = '" . (int)$catalog_id . "'");
        return $result->rows;
    }

    public function getUnits($vehicle_id) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "laximo_unit_name WHERE vehicle_id = '" . (int)$vehicle_id . "'");
        return $result->rows;
    }

    public function getUnitsByUnitId($unit_id) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "laximo_unit WHERE unit_id = '" . (int)$unit_id . "'");
        return $result->row;
    }

    public function getProduct($unit_id) {
        $this->load->model('fe/market/price');
        $price_for_id = $this->model_fe_market_price->getIdByGuid(null);

        $result = $this->db->query("SELECT p.*, pd.*, pb.name AS brand_name, pp.price, pc.crosscode AS crosscode FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "laximo_product_to_unit_name lptun ON p.product_id = lptun.product_id LEFT JOIN " . DB_PREFIX . "laximo_unit_name lun ON lptun.unit_name_id = lun.id LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id LEFT JOIN " . DB_PREFIX . "product_to_brand ptb ON p.guid = ptb.product_guid LEFT JOIN "
        . DB_PREFIX . "product_brand pb ON ptb.product_brand_guid = pb.guid LEFT JOIN " . DB_PREFIX . "product_to_crosscode ptc ON p.product_id = ptc.product_id LEFT JOIN " . DB_PREFIX . "product_crosscode pc ON ptc.crosscode_id = pc.product_crosscode_id LEFT JOIN " . DB_PREFIX . "product_price pp ON p.product_id = pp.product_id WHERE lun.id = '" . (int)$unit_id . "' AND pp.product_price_for_id = '" . (int)$price_for_id . "' GROUP BY p.product_id");

        return $result->rows;
    }

}
