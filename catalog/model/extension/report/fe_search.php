<?php
class ModelExtensionReportFeSearch extends Model {

    public function addSearch($search) {
        $prefix = DB_PREFIX;
        $search = $this->db->escape($search);
        $sql = "INSERT INTO {$prefix}fe_search SET
            `search` = '$search'";
        $this->db->query($sql);
        return $this->db->getLastId();
    }

}
