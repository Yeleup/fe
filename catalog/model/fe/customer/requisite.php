<?php
class ModelFeCustomerRequisite extends Model {

    public function add($data) {
        $data['customer_id'] = (int)$data['customer_id'];
        $data['address'] = $this->db->escape($data['address']);
        $prefix = DB_PREFIX;

        // $this->load->model('fe/util/uuid');
        // $uuid = $this->model_fe_util_uuid->generate();

        $sql = "INSERT INTO ${prefix}fe_requisite SET
            customer_id = '${data['customer_id']}',
            address = '${data['address']}',
            guid = ''";
        $this->db->query($sql);
        return $this->db->getLastId();
    }

    public function getByCustomerId($customer_id) {
        $customer_id = (int)$customer_id;
        $prefix = DB_PREFIX;

        $sql = "SELECT * FROM ${prefix}fe_requisite
            WHERE customer_id = $customer_id";

        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function getOneByIdAndCustomerId($id, $customer_id) {
        $id = (int)$id;
        $customer_id = (int)$customer_id;
        $prefix = DB_PREFIX;

        $sql = "SELECT * FROM ${prefix}fe_requisite
            WHERE
            id = $id AND
            customer_id = $customer_id
            LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function deleteByIdAndCustomerId($id, $customer_id) {
        $customer_id = (int)$customer_id;
        $id = (int)$id;
        $prefix = DB_PREFIX;
        $sql = "DELETE FROM ${prefix}fe_requisite
            WHERE
            customer_id = '$customer_id' AND
            id = '$id'";
        $this->db->query($sql);
        return $id;
    }

}
