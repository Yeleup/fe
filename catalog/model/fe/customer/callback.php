<?php
class ModelFeCustomerCallback extends Model {

    public function add($data) {
        $prefix = DB_PREFIX;
        $name = $this->db->escape($data['name']);
        $phone = $this->db->escape($data['phone']);
        $ip = $this->request->server['REMOTE_ADDR'];
        $customer_id = $this->session->data['customer_id'] ?? 0;

        if (!$name || !$phone) {
            return false;
        }

        $sql = "INSERT INTO {$prefix}fe_callback SET
            name = '$name',
            phone = '$phone',
            ip = '$ip',
            customer_id = '$customer_id'";

        $result = $this->db->query($sql);
        $id = $this->db->getLastId();

        $this->load->model('fe/util/telegram');
        $this->model_fe_util_telegram->sendNotifications("Обратный Звонок: $name $phone");

        return $id;
    }

}
