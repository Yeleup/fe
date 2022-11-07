<?php
class ModelFeCustomerStatus extends Model {

    public function get() {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_customer_status";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function getByName($name) {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_customer_status
            WHERE name = '" . $this->db->escape($name) . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
