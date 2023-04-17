<?php
class ModelFeCheckoutCoupon extends Model {

    protected $table_customer_to_coupon = DB_PREFIX . 'fe_customer_to_coupon';

    public function setActiveCoupon($coupon_code) {
        $this->session->data['fe']['active_coupon'] = $coupon_code;
    }

    public function getActiveCoupon() {
        return $this->session->data['fe']['active_coupon'] ?? null;
    }

    public function getCoupon($code) {
        $this->load->model('extension/total/coupon');
        $coupon = $this->model_extension_total_coupon->getCoupon($code);
        return $coupon;
    }

    public function getValidCouponByCustomer($code, $customer_id) {
        $coupon = $this->getCoupon($code);

        if (!$coupon) {
            return null;
        }

        if (!$this->isValidCustomer($customer_id)) {
            return null;
        }

        $coupon_uses_customer = (int)$coupon['uses_customer'];
        $coupon_id = $coupon['coupon_id'] ?? 0;
        $customer_to_coupon = $this->getCustomerToCoupon($coupon_id, $customer_id);

        $coupon_used = (int)($customer_to_coupon['times_used'] ?? 0);
        if ($coupon_uses_customer <= $coupon_used) {
            return null;
        }
        return $coupon;
    }

    private function isValidCustomer($customer_id) {
        $this->load->model('fe/customer/reg_type');
        $customer_reg_type = $this->model_fe_customer_reg_type->getByName('retail');
        $this->load->model('fe/customer/status');
        $customer_status = $this->model_fe_customer_status->getByName('physical');
        $this->load->model('fe/customer/fe_customer');
        $fe_customer = $this->model_fe_customer_fe_customer->getByCustomerId($customer_id);

        if (!$fe_customer || !$customer_status || !$customer_reg_type) {
            return false;
        }

        if ($fe_customer['reg_type'] != $customer_reg_type['id'] ||
            $fe_customer['status'] != $customer_status['id']) {
            return false;
        }

        return true;
    }

    public function addCustomerToCoupon($coupon_id, $customer_id) {
        $coupon_id = (int)$coupon_id;
        $customer_id = (int)$customer_id;
        $sql = "INSERT INTO {$this->table_customer_to_coupon} SET
            `coupon_id` = '$coupon_id',
            `customer_id` = '$customer_id'";
        $this->db->query($sql);
        return $this->db->getLastId();
    }

    public function getCustomerToCoupon($coupon_id, $customer_id) {
        $coupon_id = (int)$coupon_id;
        $customer_id = (int)$customer_id;
        $sql = "SELECT * FROM {$this->table_customer_to_coupon}
            WHERE
            `coupon_id` = '$coupon_id' AND
            `customer_id` = '$customer_id'
            LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function incrementCouponUsage($coupon_id, $customer_id) {
        $coupon_id = (int)$coupon_id;
        $customer_id = (int)$customer_id;
        $coupon_to_customer = $this->getCustomerToCoupon($coupon_id, $customer_id);
        if ($coupon_to_customer) {
            $id = $coupon_to_customer['id'];
        } else {
            $id = $this->addCustomerToCoupon($coupon_id, $customer_id);
        }
        $sql = "UPDATE {$this->table_customer_to_coupon} SET
            `times_used` = `times_used` + 1
            WHERE
            `id` = '$id'";
        $this->db->query($sql);
        return $id;
    }

}
