<?php
class ModelFeCatalogProduct extends Model {
    public function getProductByGuid($guid) {
        $guid = $this->db->escape($guid);
        $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE guid = '" . $guid . "'");
        return $result->row;
    }

    public function getGuidById($id) {
        $result = $this->db->query("SELECT guid FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$id . "'");
        return $result->row ? $result->row['guid'] : false;
    }

    public function getByRange($offset, $limit) {
        $sql = "SELECT * FROM " . DB_PREFIX . "product";
        $limit = " LIMIT " . (int)$offset . ", " . (int)$limit;
        if ($offset && $limit) {
            $sql .= $limit;
        }
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function add($data) {
        $this->load->model('catalog/product');
        $get_prod_by_guid = $this->getProductByGuid($data['guid']);

        if (!$get_prod_by_guid) {
            $data['quantity'] = 0;
            $product_id = $this->model_catalog_product->addProduct($data);
        } else {
            $data['quantity'] = $get_prod_by_guid['quantity'];
            $this->model_catalog_product->editProduct($get_prod_by_guid['product_id'], $data);
            $product_id = $get_prod_by_guid['product_id'];
        }

        /*$query = "UPDATE " . DB_PREFIX . "product SET guid = '" . $this->db->escape($data['guid']) . "' WHERE product_id = '" . (int)$product_id . "'";*/
       
		$query = "UPDATE " . DB_PREFIX . "product SET guid = '" . $this->db->escape($data['guid']) . "', status = '" . (int)$data['status'] . "' WHERE product_id = '" . (int)$product_id . "'";
		
        $this->db->query($query);

        if(isset($data['product_brand_guid'])) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_brand WHERE product_guid = '" . $this->db->escape($data['guid']) . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_brand SET product_guid = '" . $this->db->escape($data['guid']) . "', product_brand_guid = '" . $this->db->escape($data['product_brand_guid']) . "'");
        }

        return $product_id;
    }

    public function update($data) {
        $sql = "UPDATE " . DB_PREFIX . "product_description SET name = '" . $this->db->escape($data['name']) . "', meta_title = '" . $this->db->escape($data['name']) . "' WHERE product_id = '" . (int)$data['product_id'] . "';";
        // $sql .= "UPDATE " . DB_PREFIX . "product "

        $this->db->query($sql);
        return true;
    }
}
