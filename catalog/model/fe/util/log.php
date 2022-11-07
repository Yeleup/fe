<?php
class ModelFeUtilLog extends Model {

    public function error($code, $log) {
        $this->load->model('fe/util/telegram');
        $this->model_fe_util_telegram->sendNotifications($log);
        $this->log($code, $log);
    }

    public function info($code, $log) {
        $this->log($code, $log);
    }

    public function log($code, $log) {
        $prefix = DB_PREFIX;
        $log = $this->db->escape($log);
        $code = $this->db->escape($code);
        $sql = "INSERT INTO {$prefix}fe_log SET
            `code` = '{$code}',
            `log` = '{$log}'";
        $this->db->query($sql);
    }

}
