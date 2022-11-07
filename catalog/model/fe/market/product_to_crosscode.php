<?php
class ModelFeMarketProductToCrosscode extends Model {
    public function getByCrosscodeIds($crosscode_ids) {
        if (!$crosscode_ids) {
            return null;
        }
        $l = '(';
        foreach ($crosscode_ids as $id) {
            $l .= '"' . (int)$id . '",';
        }
        $l = substr($l, 0, strlen($l) - 1) . ')';

        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_crosscode WHERE crosscode_id in " . $l . "");
        return $result->rows;
    }
}