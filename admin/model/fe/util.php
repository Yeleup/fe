<?php
class ModelFeUtil extends Model {

    public function getLaximoSynchronizeOffsetGet() {
        $name = 'laximo_synchronize_offset';
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "fe_util WHERE name = '" . $this->db->escape($name) . "'");
        return $result->row ? $result->row['int_val'] : false;
    }

    public function getLaximoSynchronizeOffsetUpdate() {
        $name = 'laximo_synchronize_offset';
        $offset = $this->getLaximoSynchronizeOffsetGet();
        $result = 0;
        if ($offset !== false) {
            $result = (int)$offset;
            $new_val = (int)$offset + (1);
            $this->db->query("UPDATE " . DB_PREFIX . "fe_util SET int_val = (int_val + 1) % (SELECT count(*) AS c FROM " . DB_PREFIX . "product) WHERE name = '" . $name . "'");
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "fe_util SET name = '" . $name . "', int_val = '" . 0 . "'");
        }

        return $result;
    }

}
