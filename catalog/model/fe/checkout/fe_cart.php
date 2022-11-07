<?php
class ModelFeCheckoutFeCart extends Model {

    public function get($count = false, $offset = null, $limit = null)
    {
        $sql_select = $count ? " COUNT(*) AS count " : " * ";

        $sql = "SELECT " . $sql_select . " FROM " . DB_PREFIX . "fe_cart
            WHERE customer_id = '" . ($this->session->data['customer_id'] ?? -1) . "'";

        if (!$count && $offset && $limit) {
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



    public function copyByCartId($cart_id) {
        $this->load->model('fe/checkout/cart');
        $cart = $this->model_fe_checkout_cart->getById($cart_id);
        if (!$cart) {
            return false;
        }

        $fe_cart_id = $this->add([
            'api_id' => $cart['api_id'],
            'customer_id' => $cart['customer_id'],
            'session_id' => $cart['session_id'],
            'product_id' => $cart['product_id'],
            'recurring_id' => $cart['recurring_id'],
            'option' => $cart['option'],
            'quantity' => $cart['quantity'],
        ]);

        $this->model_fe_checkout_cart->deleteById($cart_id);

        return true;
    }



    public function add($data) {
        $sql = "INSERT INTO " . DB_PREFIX . "fe_cart SET
            api_id = '" . (int)$data['api_id'] . "',
            customer_id = '" . (int)$data['customer_id'] . "',
            session_id = '" . $this->db->escape($data['session_id']) . "',
            product_id = '" . (int)$data['product_id'] . "',
            recurring_id = '" . (int)$data['recurring_id'] . "',
            option = '" . $this->db->escape($data['option']) . "',
            quantity = '" . (int)$data['quantity'] . "'";
        $result = $this->db->query($sql);
        $id = $this->db->getLastId();
        return $id;
    }



    public function delete($id) {
        $sql = "DELETE FROM " . DB_PREFIX . "fe_cart
            WHERE id = '" . (int)$id . "'
            AND customer_id = '" . ($this->session->data['customer_id'] ?? -1) . "'";
        $result = $this->db->query($sql);
        return $id;
    }

}
