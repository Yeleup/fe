<?php
class ModelFeCustomerAddress extends Model {

    public function add($data) {
        $sql = "INSERT INTO " . DB_PREFIX . "customer_fe_address SET
        customer_id = '" . (int)$data['customer_id'] . "',
        region = '" . $this->db->escape($data['region']) . "',
        town = '" . $this->db->escape($data['town']) . "',
        street = '" . $this->db->escape($data['street']) . "',
        house = '" . $this->db->escape($data['house']) . "',
        entrance = '" . $this->db->escape($data['entrance']) . "',
        apartment = '" . $this->db->escape($data['apartment']) . "'";
        $result = $this->db->query($sql);
        return true;
    }

    public function getByIdAndCustomerId($id, $customer_id) {
        $prefix = DB_PREFIX;
        $id = (int)$id;
        $customer_id = (int)$customer_id;
        $sql = "SELECT * FROM {$prefix}customer_fe_address
            WHERE id = '$id' AND customer_id = '$customer_id'
            LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function getAllByCustomerId($customer_id) {
        $prefix = DB_PREFIX;
        $customer_id = (int)$customer_id;
        $sql = "SELECT * FROM {$prefix}customer_fe_address
            WHERE customer_id = '$customer_id'";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function deleteByIdAndCustomerId($id, $customer_id) {
        $prefix = DB_PREFIX;
        $id = (int)$id;
        $customer_id = (int)$customer_id;
        $sql = "DELETE FROM {$prefix}customer_fe_address
            WHERE id = '$id' AND customer_id = '$customer_id'";
        $this->db->query($sql);
        return $id;
    }

}
