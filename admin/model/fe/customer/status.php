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



    public function add($data) {
        $result = $this->getByName($data['name']);
        if ($result) {
            return $result['id'];
        }

        $sql = "INSERT INTO " . DB_PREFIX . "fe_customer_status SET
            name = '" . $this->db->escape($data['name']) . "',
            name_display = '" . $this->db->escape($data['name_display']) . "'";

        if ($data['id'] ?? false) {
            $sql_id = ", id = '" . (int)$data['id'] . "'";
            $sql .= $sql_id;
        }

        $result = $this->db->query($sql);
        $id = $this->db->getLastId();
        return $id;
    }



    public function deleteByName($name) {
        $sql = "DELETE FROM " . DB_PREFIX . "fe_customer_status
            WHERE name = '" . $this->db->escape($name) . "'";
        $result = $this->db->query($sql);
        return true;
    }

}
