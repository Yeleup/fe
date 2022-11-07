<?php
class ModelFeMarketBrand extends Model {
    public function getBrandByGuid($guid) {
        $result = $this->db->query("SELECT product_brand_id, guid AS brand_guid FROM " . DB_PREFIX . "product_brand WHERE guid = '" . $guid . "'");
        return $result->row;
    }

    public function addBrand($data) {
        $result = $this->getBrandByGuid($data['guid']);
        if ($result) {
            return $result['product_brand_id'];
        }
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_brand SET guid = '" . $this->db->escape($data['guid']) . "', name = '" . $this->db->escape($data['name']) . "'");
        $brand_id = $this->db->getLastId();
        return $brand_id;
    }
}