<?php
class ModelFeCustomerRegType extends Model {

    public function getByName($name) {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_customer_reg_type
            WHERE name = '" . $this->db->escape($name) . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function getById($id) {
        $prefix = DB_PREFIX;
        $id = (int)$id;
        $sql = "SELECT * FROM ${prefix}fe_customer_reg_type
            WHERE id = '$id'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
