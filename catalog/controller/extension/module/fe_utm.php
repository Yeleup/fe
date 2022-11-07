<?php

class ControllerExtensionModuleFeUtm extends Controller {

    protected $table_utm = DB_PREFIX . 'fe_utm';

    private function addUtm($name) {
        $name = $this->db->escape($name);
        $utm = $this->getUtmByName($name);
        if ($utm) {
            $sql = "UPDATE {$this->table_utm} SET
                `count` = `count` + 1
                WHERE
                `name` = '{$name}'";
        } else {
            $sql = "INSERT INTO {$this->table_utm} SET
                `name` = '{$name}'";
        }
        $this->db->query($sql);
        return true;
    }

    private function getUtmByName($name) {
        $name = $this->db->escape($name);
        $sql = "SELECT * FROM {$this->table_utm}
            WHERE
            `name` = '{$name}'
            LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function register() {
        $utm = $this->request->get['utm'] ?? null;
        if ($utm) {
            $this->addUtm($utm);
        }
    }

}
