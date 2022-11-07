<?php
class ModelFeCheckoutFeOrderStatus extends Model {

    public function add($data) {
        $sql = "INSERT INTO " . DB_PREFIX . "fe_order_status SET
            id = '" . (int)$data['id'] . "',
            code = '" . $this->db->escape($data['code']) . "',
            display = '" . $this->db->escape($data['display']) . "'";
        $result = $this->db->query($sql);
        $id = $this->db->getLastId();
        return $id;
    }



    public function deleteAll() {
        $sql = "DELETE FROM " . DB_PREFIX . "fe_order_status";
        $result = $this->db->query($sql);
        return true;
    }



    public function getByCode($code) {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_order_status
            WHERE code = '" . $this->db->escape($code) . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
