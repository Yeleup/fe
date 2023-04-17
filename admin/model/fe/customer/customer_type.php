<?php
class ModelFeCustomerCustomerType extends Model {

    public function add($data) {
        $prefix = DB_PREFIX;
        $data['name'] = $this->db->escape($data['name']);
        $data['display'] = $this->db->escape($data['display']);

        $result = $this->getByName($data['name']);
        if ($result) {
            return $result['id'];
        }

        $sql = "INSERT INTO {$prefix}fe_customer_type SET
            name = '{$data['name']}',
            display = '{$data['display']}'";
        $this->db->query($sql);
        return $this->db->getLastId();
    }

    public function getByName($name) {
        $name = $this->db->escape($name);
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM {$prefix}fe_customer_type WHERE name = '$name' LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function getAll() {
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM {$prefix}fe_customer_type";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function getById($id) {
        $id = (int)$id;
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM {$prefix}fe_customer_type WHERE id = '$id' LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
