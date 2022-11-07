<?php
class ModelFeMarketCleanup extends Model {

    // Clean DB
    public function clean() {
        $this->product();
    }


    // Clean Product Table
    public function product() {
        $sql = "DELETE FROM " . DB_PREFIX . "product
            WHERE guid IS NULL";
        $this->db->query($sql);

        $sql = "DELETE pd FROM " . DB_PREFIX . "product_description pd
            LEFT JOIN " . DB_PREFIX . "product p USING(product_id)
            WHERE p.product_id IS NULL";
        $this->db->query($sql);
    }

}
