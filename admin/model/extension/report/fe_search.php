<?php
class ModelExtensionReportFeSearch extends Model {

    private function getSearchesSql($filter) {
        $filter_date_start = $this->db->escape($filter['filter_date_start'] ?? '');
        $filter_date_end = $this->db->escape($filter['filter_date_end'] ?? '');
        $prefix = DB_PREFIX;

        $sql = "SELECT search, COUNT(search) as amount FROM {$prefix}fe_search
            WHERE 1";

        if ($filter_date_start) {
            $sql .= " AND `created_at` >= DATE('{$filter_date_start}')";
        }

        if ($filter_date_end) {
            $sql .= " AND `created_at` <= DATE('{$filter_date_end}')";
        }

        $sql .= "
            GROUP BY search
            ORDER BY amount DESC
        ";

        return $sql;
    }

    public function getTotalSearches($filter) {
        $sql = $this->getSearchesSql($filter);
        $sql = "SELECT COUNT(search) as amount FROM ({$sql}) t1";
        $result = $this->db->query($sql);
        return (int)($result->row['amount'] ?? 0);
    }

    public function getSearches($filter) {
        $limit = (int)($filter['filter_limit'] ?? 20);
        $offset = (int)($filter['filter_offset'] ?? 0);

        $sql = $this->getSearchesSql($filter);

        $sql .= " LIMIT $offset, $limit";

        $result = $this->db->query($sql);
        return $result;
    }

}
