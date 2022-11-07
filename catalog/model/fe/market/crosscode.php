<?php
class ModelFeMarketCrosscode extends Model {
    public function getByCrosscode($crosscode) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_crosscode WHERE crosscode = '" . $this->db->escape($crosscode) . "'");
        return $result->rows;
    }

    public function getByCrosscodes($crosscodes) {
        if (!$crosscodes) {
            return null;
        }

        $l = '(';
        foreach ($crosscodes as $crosscode) {
            $l .= '"' . $this->db->escape($crosscode) . '",';
        }
        $l = substr($l, 0, strlen($l) - 1) . ')';

        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_crosscode WHERE crosscode in " . $l . "");
        return $result->rows;
    }

    public function getOneCrosscodeByProductId($product_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "product_to_crosscode ptc
            LEFT JOIN " . DB_PREFIX . "product_crosscode pc
                ON ptc.crosscode_id = pc.product_crosscode_id
            WHERE ptc.product_id = '" . (int)$product_id . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function getCrosscodesByProductId($product_id) {
        $sql = "SELECT pc.* FROM " . DB_PREFIX . "product_to_crosscode ptc
            JOIN " . DB_PREFIX . "product_crosscode pc
                ON ptc.crosscode_id = pc.product_crosscode_id
            WHERE ptc.product_id = '" . (int)$product_id . "'";
        $result = $this->db->query($sql);
        return $result->rows;
    }
}
