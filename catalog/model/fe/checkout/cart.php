<?php
class ModelFeCheckoutCart extends Model {

    public function getById($id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "cart
            WHERE cart_id = '" . (int)$id . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function deleteById($id)
    {
        $sql = "DELETE FROM " . DB_PREFIX . "cart
            WHERE cart_id = '" . (int)$id . "'";
        $result = $this->db->query($sql);
        return $result;
    }

    public function deleteByCustomerId($customer_id) {
        $prefix = DB_PREFIX;
        $customer_id = (int)$customer_id;
        $sql = "DELETE FROM {$prefix}cart WHERE customer_id = '{$customer_id}'";
        $this->db->query($sql);
    }

}
