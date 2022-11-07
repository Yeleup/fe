<?php
class ModelFeMarketResponsible extends Model {

    public function getById($id) {
        $id = (int)$id;
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM {$prefix}fe_responsibles WHERE id = '$id' LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
