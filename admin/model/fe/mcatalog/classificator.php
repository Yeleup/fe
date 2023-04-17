<?php
class ModelFeMcatalogClassificator extends Model {

    public function add($data) {
        $data['guid'] = $this->db->escape($data['guid']);
        $data['name'] = $this->db->escape($data['name']);
        $prefix = DB_PREFIX;
        $sql = "INSERT INTO ${prefix}fe_m_classificator SET
            guid = '${data['guid']}',
            name = '${data['name']}'";
        $this->db->query($sql);
        return $this->db->getLastId();
    }

    public function getByGuid($guid) {
        $guid = $this->db->escape($guid);
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM ${prefix}fe_m_classificator
            WHERE guid = '$guid'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
