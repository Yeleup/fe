<?php
class ModelFeMarketClientCategory extends Model {

    public function getIdWhereRetail () {
        $sql = "SELECT id FROM " . DB_PREFIX . "fe_client_category
            WHERE guid = '633aca59-b39d-11eb-832c-8030e02ddc2f'";
        $result = $this->db->query($sql);
        return $result->row ? $result->row['id'] : false;
    }

    public function getWhereRetail () {
        $sql = "SELECT id FROM " . DB_PREFIX . "fe_client_category
            WHERE guid = '633aca59-b39d-11eb-832c-8030e02ddc2f'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function getByCustomerId($customer_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_customer c
            INNER JOIN " . DB_PREFIX . "fe_client_category cc ON c.client_category_id = cc.id
            WHERE c.customer_id = '" . (int)$customer_id . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
