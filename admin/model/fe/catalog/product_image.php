<?php
class ModelFeCatalogProductImage extends Model {

    protected $table = DB_PREFIX . 'product_image';

    public function getByProductIdAndImage($product_id, $image) {
        $product_id = (int)$product_id;
        $image = $this->db->escape($image);
        $sql = "SELECT * FROM {$this->table}
            WHERE
            `product_id` = '{$product_id}' AND
            `image` = '{$image}'";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function add($data) {
        $data['product_id'] = (int)$data['product_id'];
        $data['image'] = $this->db->escape($data['image']);
        $data['sort_order'] = (int)$data['sort_order'];
        $sql = "INSERT INTO {$this->table} SET
            `product_id` = '{$data['product_id']}',
            `image` = '{$data['image']}',
            `sort_order` = '{$data['sort_order']}'";
        $result = $this->db->query($sql);
        return $result ? $this->db->getLastId() : 0;
    }

}
