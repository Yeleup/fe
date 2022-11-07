<?php
class ModelFeCheckoutFeOrder extends Model {

    public function getById($id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_order
            WHERE id = '" . (int)$id . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }



    public function add($data) {
        if (!is_array($data['cart_info'])) {
            return false;
        }

        $this->load->model('fe/checkout/fe_order_status');
        $order_status = $this->model_fe_checkout_fe_order_status->getByCode('pending');
        if (!$order_status) {
            return false;
        }

        $sql = "INSERT INTO " . DB_PREFIX . "fe_order SET
            customer_id = '" . (int)$data['customer_id'] . "',
            address = '" . $this->db->escape(json_encode($data['address'])) . "',
            customer_info = '" . $this->db->escape(json_encode($data['customer_info'] ?? [])) . "',
            cart_info = '" . $this->db->escape(json_encode($data['cart_info'])) . "',
            status_id = '" . (int)$order_status['id'] . "'";
        $this->db->query($sql);
        $id = $this->db->getLastId();
        return $id;
    }



    public function updateGuidById($id, $guid) {
        $sql = "UPDATE " . DB_PREFIX . "fe_order SET
            guid = '" . $this->db->escape($guid) . "'
            WHERE id = '" . (int)$id . "'";
        $result = $this->db->query($sql);
        return $id;
    }



    public function updateStatusById($id, $status) {
        $this->load->model('fe/checkout/fe_order_status');
        $order_status = $this->model_fe_checkout_fe_order_status->getByCode($status);
        if (!$order_status) {
            return false;
        }

        $order = $this->getById($id);
        if (!$order) {
            return false;
        }
        $sql = "UPDATE " . DB_PREFIX . "fe_order SET
            status_id = '" . (int)$order_status['id'] . "'
            WHERE id = '" . (int)$id . "'";
        $result = $this->db->query($sql);
        return $result ? $id : false;
    }

}
