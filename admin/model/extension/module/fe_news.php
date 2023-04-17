<?php

class ModelExtensionModuleFeNews extends Model {

    protected $table = DB_PREFIX . "information_news";

    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM {$this->table}
            WHERE
            `id` = '$id'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function add($id) {
        $id = (int)$id;
        $sql = "INSERT IGNORE INTO {$this->table} SET
            `id` = '$id'";
        $this->db->query($sql);
        return $id;
    }

    public function delete($id) {
        $id = (int)$id;
        $sql = "DELETE FROM {$this->table}
            WHERE
            `id` = '$id'";
        $this->db->query($sql);
    }

}
