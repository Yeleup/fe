<?php
class ModelFeCustomerOrder extends Model {

    protected $status = [
        1 => 'Ожидается',
        2 => 'Завершен',
    ];

    public function getStatusName($status) {
        return $this->status[$status];
    }

    public function get($count = false, $offset = null, $limit = null) {
        $this->load->model('fe/checkout/fe_order_status');
        // $this->model_fe_checkout_fe_order_status->getByCode('');

        $sql_select = $count ? " COUNT(*) AS count " : " * ";
        $sql = "SELECT " . $sql_select . " FROM " . DB_PREFIX . "fe_order
            WHERE 1 ";

        $sql .= " ORDER BY created_at DESC";

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
