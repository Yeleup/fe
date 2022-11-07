<?php
class ModelFeMarketResponsible extends Model {

    public function getAll() {
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM ${prefix}fe_responsibles";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function getByGuid($guid) {
        $guid = $this->db->escape($guid);
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM ${prefix}fe_responsibles WHERE guid = '$guid' LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function add($data) {
        $result = $this->getByGuid($data['guid']);
        if ($result) {
            return $result['id'];
        }
        $data['name'] = $this->db->escape($data['name']);
        $data['guid'] = $this->db->escape($data['guid']);
        $prefix = DB_PREFIX;
        $sql = "INSERT INTO ${prefix}fe_responsibles SET
            name = '${data['name']}',
            guid = '${data['guid']}'";
        $this->db->query($sql);
        return $this->db->getLastId();
    }

}
