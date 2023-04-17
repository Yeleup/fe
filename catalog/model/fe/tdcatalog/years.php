<?php
class ModelFeTdcatalogYears extends Model {

    public function get($model_id) {
        return json_encode($this->getLocal($model_id));
    }



    private function getTd($model_id) {
        $this->load->library('TdcatalogGuzzleFacade');
        $client = new TdcatalogGuzzleFacade();
        $result = $client->get('/years/' . $model_id);
        return $result;
    }



    private function getLocal($model_id) {
        $sql = "SELECT
            id,
            description AS name,
            construction_interval AS displayvalue
            FROM " . DB_PREFIX . "fe_td_modification
            WHERE model_id = '" . (int)$model_id . "'";
        $result = $this->db->query($sql);
        return $result->rows;
    }

}
