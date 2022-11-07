<?php
class ModelFeCustomerCallback extends Model {

    public function get($count = false, $offset = null, $limit = null)
    {
        $sql_select = $count ? " COUNT(*) AS count " : " * ";

        $sql = "SELECT " . $sql_select . " FROM " . DB_PREFIX . "fe_callback";

        $offset = $offset ?? 0;
        $limit = $limit ?? 12;

        if (!$count) {
            $sql_limit = " LIMIT %s, %s ";
            $sql_limit = sprintf($sql_limit, $offset, $limit);
            $sql .= $sql_limit;
        }

        $result = $this->db->query($sql);

        if ($count) {
            return $result->row;
        } else {
            return $result->rows;
        }
    }

}
