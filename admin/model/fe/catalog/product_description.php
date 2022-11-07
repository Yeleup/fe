<?php
class ModelFeCatalogProductDescription extends Model {

    public function add($data) {
        $sql = "INSERT INTO " . DB_PREFIX . "product_description SET
        product_id = '" . (int)$data['product_id'] . "',
        language_id = '" . (int)$data['language_id'] . "',
        name = '" . $this->db->escape($data['name']) . "',
        description = '" . $this->db->escape($data['description']) . "',
        tag = '" . $this->db->escape($data['tag']) . "',
        meta_title = '" . $this->db->escape($data['meta_title']) . "',
        meta_description = '" . $this->db->escape($data['meta_description']) . "',
        meta_keyword = '" . $this->db->escape($data['meta_keyword']) . "'";
        $result = $this->db->query($sql);
        return $result ? $this->db->getLastId() : false;
    }

}
