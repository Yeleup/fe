<?php
class ModelFeCustomerRegType extends Model {

    public function get() {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_customer_reg_type";
        $result = $this->db->query($sql);
        return $result->rows;
    }



    public function getByName($name) {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_customer_reg_type
            WHERE name = '" . $this->db->escape($name) . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }



    public function add($data) {
        $result = $this->getByName($data['name']);
        if ($result) {
            return $result['id'];
        }

        $sql = "INSERT INTO " . DB_PREFIX . "fe_customer_reg_type SET
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
        $sql = "DELETE FROM " . DB_PREFIX . "fe_customer_reg_type
            WHERE name = '" . $this->db->escape($name) . "'";
        $result = $this->db->query($sql);
        return true;
    }

}
