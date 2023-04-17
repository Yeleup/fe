<?php
class ModelFeTdcatalogBrands extends Model {

    public function get() {
        return json_encode($this->getLocal());
    }



    private function getTd() {
        $this->load->library('TdcatalogGuzzleFacade');
        $client = new TdcatalogGuzzleFacade();
        $result = $client->get('/brands');
        return $result;
    }



    private function getLocal() {
        $sql = "SELECT id, description AS name
            FROM " . DB_PREFIX . "fe_td_brand";
        $result = $this->db->query($sql);
        return $result->rows;
    }

}
