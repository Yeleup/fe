<?php
class ModelFeCheckoutOrder extends Model {

    public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = false, $override = false) {
        $this->load->model('sale/order');
        $order_info = $this->model_sale_order->getOrder($order_id);

        if ($order_info) {
            // Update the DB with the new statuses
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

            $this->cache->delete('product');
        }
    }

    public function updateGuidById($id, $guid) {
        $prefix = DB_PREFIX;
        $id = (int)$id;
        $guid = $this->db->escape($guid);

        $sql = "UPDATE {$prefix}order
            SET `guid` = '$guid'
            WHERE `order_id` = '$id'";
        $this->db->query($sql);
        return $id;
    }

}
