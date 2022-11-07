<?php
class ModelFeCustomerFeCustomer extends Model {

    public function add($data) {
        $this->load->model('fe/customer/reg_type');
        $this->load->model('fe/customer/status');
        $this->load->model('fe/market/client_category');

        if ($data['reg_type'] === 'retail') {
            $client_category_id = $this->model_fe_market_client_category->getIdWhereRetail();
        }

        $sql = "INSERT INTO " . DB_PREFIX . "fe_customer SET
        customer_id = '" . (int)$data['customer_id'] . "',
        reg_type = '" . $this->model_fe_customer_reg_type->getByName($data['reg_type'])['id'] . "',
        status = '" . $this->model_fe_customer_status->getByName($data['status'])['id'] . "',
        iin = '" . $this->db->escape($data['iin'] ?? null) . "',
        company_name = '" . $this->db->escape($data['company_name'] ?? null) . "',
        company_name_full = '" . $this->db->escape($data['company_name_full'] ?? null) . "',
        official_address = '" . $this->db->escape($data['official_address'] ?? null) . "',
        address = '" . $this->db->escape($data['address'] ?? null) . "',
        client_category_id = '" . (int)$client_category_id . "'";
        $this->db->query($sql);
        return true;
    }

    public function getByCustomerId($customer_id) {
        $prefix = DB_PREFIX;
        $customer_id = (int)$customer_id;
        $sql = "SELECT * FROM ${prefix}fe_customer
            WHERE customer_id = '$customer_id'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
