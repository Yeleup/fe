<?php
class ModelFeMarketPriceFor extends Model {
    public function getPriceForByGuid($guid) {
        $result = $this->db->query("SELECT product_price_for_id, guid AS price_for_guid FROM " . DB_PREFIX . "product_price_for WHERE guid = '" . $this->db->escape($guid) . "'");
        return $result->row;
    }

    public function get() {
        $sql = "SELECT * FROM " . DB_PREFIX . "product_price_for";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function getIdByGuid($guid) {
        $result = $this->db->query("SELECT product_price_for_id AS id FROM " . DB_PREFIX . "product_price_for WHERE guid = '" . $this->db->escape($guid) . "'");
        return $result->row ? $result->row['id'] : false;
    }

    public function addPriceFor($data) {
        $price_for = $this->getPriceForByGuid($data['guid']);
        if (!$price_for) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_price_for SET guid = '" . $this->db->escape($data['guid']) . "', name = '" . $this->db->escape($data['name']) . "'");
            $price_for_id = $this->db->getLastId();
            return $price_for_id;
        } else {
            return $price_for['product_price_for_id'];
        }
    }
}
