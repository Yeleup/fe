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

}
