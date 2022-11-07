<?php
class ModelFeMarketSubcategory extends Model {

    public function add($data) {
        $data['name'] = $this->db->escape($data['name']);
        $prefix = DB_PREFIX;
        $sql = "INSERT INTO ${prefix}product_fe_subcategory SET
            name = '${data['name']}'";
        $this->db->query($sql);
        return $this->db->getLastId();
    }

    public function getByName($name) {
        $name = $this->db->escape($name);
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM ${prefix}product_fe_subcategory
            WHERE name = '$name'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function addOrGetId($data) {
        $result = $this->getByName($data['name']);

        if ($result) {
            return $result['id'];
        }

        return $this->add($data);
    }

    public function addToProduct($product_id, $data) {
        $data['product_id'] = (int)$product_id;
        $prefix = DB_PREFIX;

        $subcategory_id = $this->addOrGetId(['name' => $data['name']]);

        $sql = "INSERT IGNORE INTO ${prefix}product_to_fe_subcategory SET
            product_id = '${data['product_id']}',
            subcategory_id = '$subcategory_id'";
        $this->db->query($sql);
        return $this->db->getLastId();
    }

}
