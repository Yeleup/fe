<?php

class ModelExtensionFeLaximoLaximo extends Model {
    protected $table_product = DB_PREFIX . "product";
    protected $table_ptc = DB_PREFIX . "product_to_crosscode";
    protected $table_crosscode = DB_PREFIX . "product_crosscode";

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->library('laximo');
    }

    public function getProductsByOem($oem) {
        $oem = $this->db->escape($oem);

        $this->load->model('fe/util/crosscode');
        $oem = $this->model_fe_util_crosscode->normalize($oem);

        $oems_list = [$oem => 1];

        $find_oem_result = $this->laximo->FindOem($oem);
        $detail_ids = [];
        if ($find_oem_result['row'] ?? false) {
            foreach ($find_oem_result['row'] as $k => $v) {
                $oems_list[$v['@attributes']['oem']] = 1;
                $detail_ids[$v['@attributes']['detailid']] = 1;
            }
        }

        $detail_ids = array_slice($detail_ids, 0, 1, true);

        foreach ($detail_ids as $k => $v) {
            $result_oems = $this->laximo->FindReplacementsOemOnly($k);
            foreach ($result_oems['row'] as $result_oem) {
                $oems_list[$result_oem] = 1;
            }
        }

        $sql_oems = "'" . implode("','", array_keys($oems_list)) . "'";

        $sql = "SELECT p.* FROM {$this->table_product} p
            JOIN {$this->table_ptc} ptc ON p.product_id = ptc.product_id
            JOIN {$this->table_crosscode} c ON ptc.crosscode_id = c.product_crosscode_id
            WHERE
            c.crosscode IN($sql_oems)";

        $result = $this->db->query($sql);

        return $result->rows;
    }

    public function getProductsByOemCount($oem) {
        //TODO implement getProductsByOemCount method
        return 10;
    }

}
