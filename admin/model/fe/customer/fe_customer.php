<?php
class ModelFeCustomerFeCustomer extends Model {

    protected $table = DB_PREFIX . "fe_customer";

    public function getById($id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_customer
            WHERE customer_id = '" . (int)$id . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function updateClientCategory($customer_id, $client_category_id) {
        $customer_id = (int)$customer_id;
        $client_category_id = (int)$client_category_id;
        $prefix = DB_PREFIX;
        $sql = "UPDATE ${prefix}fe_customer SET
            client_category_id = '$client_category_id'
            WHERE
            customer_id = '$customer_id'";
        $this->db->query($sql);
        return $customer_id;
    }

    public function updateById($customer_id, $data) {
        $customer_id = (int)$customer_id;

        $this->db->query("UPDATE " . DB_PREFIX . "fe_customer SET reg_type = '" . (int)$data['reg_type'] . "', status = '" . (int)$data['status'] . "', iin = '" . $this->db->escape($data['iin']) . "', company_name = '" . $this->db->escape($data['company_name']) . "', company_name_full = '" . $this->db->escape($data['company_name_full']) . "', official_address = '" . $this->db->escape($data['official_address']) . "', address = '" . $this->db->escape($data['address']) . "', client_category_id = '" . (int)$data['client_category_id'] . "', customer_type_id = '" . (int)$data['customer_type_id'] . "', responsible_id = '" . (int)$data['responsible_id'] . "' WHERE customer_id = '" . (int)$customer_id . "'");

        return $customer_id;
    }

}
