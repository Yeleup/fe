<?php
class ModelFeCheckoutFeOrderStatus extends Model {

    public function getByCode($code) {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_order_status
            WHERE code = '" . $this->db->escape($code) . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
