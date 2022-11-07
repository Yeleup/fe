<?php
class ModelFeMcatalogClassificator extends Model {

    public function getByGuid($guid) {
        $guid = $this->db->escape($guid);
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM ${prefix}fe_m_classificator
            WHERE guid = '$guid'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
