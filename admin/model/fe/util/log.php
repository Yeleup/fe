<?php
class ModelFeUtilLog extends Model {

    public function info($code, $log) {
        $this->log($code, $log);
    }

    public function log($code, $log) {
        $prefix = DB_PREFIX;
        $log = $this->db->escape($log);
        $code = $this->db->escape($code);
        $sql = "INSERT INTO {$prefix}fe_log SET
            code = '{$code}',
            log = '{$log}'";
        $this->db->query($sql);
    }



    private function getLogsAddFilter($filter) {
        $code = $this->db->escape($filter['code'] ?? '');
        $date_end = $this->db->escape($filter['date_end'] ?? '');
        $date_start = $this->db->escape($filter['date_start'] ?? '');
        $sql = "";
        $conditions = [];
        if ($code) {
            $conditions[] = " `code` = '{$code}' ";
        }
        if ($date_end) {
            $conditions[] .= " `created_at` <= '{$date_end}' ";
        }
        if ($date_start) {
            $conditions[] .= " `created_at` >= '{$date_start}' ";
        }

        $sql .= implode(' AND ', $conditions);

        return $sql ? " WHERE {$sql} " : "";
    }

    public function getLogsCount($filter = null) {
        $prefix = DB_PREFIX;
        $sql = "SELECT COUNT(*) AS count FROM {$prefix}fe_log";
        $sql = $sql . $this->getLogsAddFilter($filter);
        $result = $this->db->query($sql);
        return $result->row['count'] ?? null;
    }

    public function getLogs($filter = null) {
        $prefix = DB_PREFIX;
        $limit = $filter['limit'] ?? 20;
        $offset = $filter['offset'] ?? 0;
        $sql = "SELECT * FROM {$prefix}fe_log";
        $sql = $sql . $this->getLogsAddFilter($filter);
        $sql .= " ORDER BY `created_at` DESC ";
        $sql .= " LIMIT {$offset},{$limit} ";
        $result = $this->db->query($sql);
        return $result->rows;
    }



}
