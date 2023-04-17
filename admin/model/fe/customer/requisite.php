<?php
class ModelFeCustomerRequisite extends Model {

    protected $table = DB_PREFIX . "fe_requisite";

    public function add($data) {
        $data['customer_id'] = (int)$data['customer_id'];
        $data['address'] = $this->db->escape($data['address']);
        $data['guid'] = $this->db->escape($data['guid']);

        $prefix = DB_PREFIX;

        $sql = "INSERT IGNORE INTO ${prefix}fe_requisite SET
            customer_id = '${data['customer_id']}',
            address = '${data['address']}',
            guid = '${data['guid']}'";

        $this->db->query($sql);
        return $this->db->getLastId();
    }

    public function getByGuid($guid) {
        $guid = $this->db->escape($guid);
        $sql = "SELECT * FROM {$this->table}
            WHERE
            `guid` = '{$guid}'";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function getByCustomerId($customer_id) {
        $customer_id = (int)$customer_id;
        $prefix = DB_PREFIX;

        $sql = "SELECT * FROM ${prefix}fe_requisite
            WHERE customer_id = $customer_id";

        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function updateById($id, $data) {
        $id = (int)$id;
        $data['guid'] = $this->db->escape($data['guid']);
        $data['customer_id'] = (int)$data['customer_id'];
        $data['address'] = $this->db->escape($data['address']);
        $prefix = DB_PREFIX;

        $sql = "UPDATE ${prefix}fe_requisite SET
            guid = '${data['guid']}',
            customer_id = '${data['customer_id']}',
            address = '${data['address']}'
            WHERE id = '$id'";
        $this->db->query($sql);
        return $id;
    }

    public function deleteById($id) {
        $id = (int)$id;
        $prefix = DB_PREFIX;

        $sql = "DELETE FROM ${prefix}fe_requisite WHERE id = '$id'";
        $this->db->query($sql);
        return $id;
    }

}
