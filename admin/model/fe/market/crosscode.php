<?php
class ModelFeMarketCrosscode extends Model {
    public function getByCrosscodeId($crosscode) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_crosscode WHERE crosscode = '" . $crosscode . "' LIMIT 1");
        return $result->row;
    }

    public function getById($id) {
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_crosscode WHERE product_crosscode_id = '" . (int)$id . "'");
        return $result->row;
    }

    public function getByProductId($product_id) {
        $sql = "SELECT pc.* FROM " . DB_PREFIX . "product p
        INNER JOIN " . DB_PREFIX . "product_to_crosscode ptc USING(product_id)
        INNER JOIN " . DB_PREFIX . "product_crosscode pc ON ptc.crosscode_id = pc.product_crosscode_id
        WHERE p.product_id = '" . (int)$product_id . "'";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function addCrosscode($data) {
        $this->load->model('fe/util/crosscode');
        $crosscode = $this->model_fe_util_crosscode->normalize($data['crosscode']);
        $result = $this->getByCrosscodeId($crosscode);
        if ($result) {
            return $result['product_crosscode_id'];
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_crosscode SET crosscode = '" . $this->db->escape($crosscode) . "'");
            $id = $this->db->getLastId();
            return $id;
        }
    }
}
