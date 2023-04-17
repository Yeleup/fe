<?php
class ModelFeCheckoutOrderStatus extends Model {

    public function getByName($name) {
        $this->load->model('fe/localization/language');
        $language_id = $this->model_fe_localization_language->getCurrentId();
        $sql = "SELECT * FROM " . DB_PREFIX . "order_status
            WHERE language_id = '" . ((int)$language_id ?? 1) . "'
            AND name = '" . $this->db->escape($name) . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
