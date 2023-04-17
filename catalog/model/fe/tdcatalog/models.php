<?php
class ModelFeTdcatalogModels extends Model {

    public function get($brand_id) {
        return json_encode($this->getLocal($brand_id));
    }



    private function getTd($brand_id) {
        $this->load->library('TdcatalogGuzzleFacade');
        $client = new TdcatalogGuzzleFacade();
        $result = $client->get('/models/' . $brand_id);
        return $result;
    }



    private function getLocal($brand_id) {
        $sql = "SELECT id, description AS name, construction_interval AS constructioninterval
            FROM " . DB_PREFIX . "fe_td_model
            WHERE brand_id = '" . (int)$brand_id . "'";
        $result = $this->db->query($sql);
        return $result->rows;
    }

}
