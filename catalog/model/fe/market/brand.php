<?php
class ModelFeMarketBrand extends Model {

    public function getByProductGuid($product_guid) {
        $sql = "SELECT * FROM " . DB_PREFIX . "product_to_brand ptb
            LEFT JOIN " . DB_PREFIX . "product_brand pb
                ON ptb.product_brand_guid = pb.guid
            WHERE ptb.product_guid = '" . $this->db->escape($product_guid) . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

}
