<?php
class ModelFeCatalogCategory extends Model {
    public function getCategoryByGuid($guid) {
        $result = $this->db->query("SELECT category_id, guid AS category_guid FROM " . DB_PREFIX . "category WHERE guid = '" . $this->db->escape($guid) . "'");
        return $result->row;
    }

    public function getIdByGuid($guid) {
        $result = $this->db->query("SELECT category_id AS id FROM " . DB_PREFIX . "category WHERE guid = '" . $this->db->escape($guid) . "'");
        return $result->row ? $result->row['id'] : false;
    }

    public function addCategory($data) {
        $this->load->model('catalog/category');
        $this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE guid = '" . $this->db->escape($data['guid']) . "'");
        $category_id = $this->model_catalog_category->addCategory($data);
        $query = "UPDATE " . DB_PREFIX . "category SET guid = '" . $this->db->escape($data['guid']) . "' WHERE category_id = '" . (int)$category_id . "'";
        $this->db->query($query);
        return $category_id;
    }
}