<?php
class ModelFeCustomerCustomerType extends Model {

    public function getById($id) {
        $id = (int)$id;
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM {$prefix}fe_customer_type WHERE id = '$id' LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
