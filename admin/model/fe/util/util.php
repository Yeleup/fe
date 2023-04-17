<?php
class ModelFeUtilUtil extends Model {

    public function getByName($name) {
        $name = $this->db->escape($name);
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM ${prefix}fe_util
            WHERE name = '$name'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function add($name, $int_val, $char_val) {
        $name = $this->db->escape($name);
        $int_val = (int)$int_val;
        $char_val = $this->db->escape($char_val);
        $prefix = DB_PREFIX;
        $sql = "INSERT INTO ${prefix}fe_util SET
            name = '$name',
            int_val = '$int_val',
            char_val = '$char_val'";
        $result = $this->db->query($sql);
        return $this->db->getLastId();
    }

    public function upsert($name, $int_val, $char_val) {
        $name = $this->db->escape($name);
        $int_val = (int)$int_val;
        $char_val = $this->db->escape($char_val);
        $prefix = DB_PREFIX;
        $result = $this->getByName($name);

        if ($result) {
            $sql = "UPDATE ${prefix}fe_util SET
                int_val = '$int_val',
                char_val = '$char_val'
                WHERE name = '$name'";
            $this->db->query($sql);
            return $result['id'];
        } else {
            $result = $this->add($name, $int_val, $char_val);
        }

        return $result;
    }

}
