<?php
class ModelFeTdcatalogSections extends Model {

    public function get($modification_id, $parent_id = null) {
        return json_encode($this->getLocal($modification_id, $parent_id));
    }



    private function getTd($modification_id, $parent_id = null) {
        $this->load->library('TdcatalogGuzzleFacade');
        $client = new TdcatalogGuzzleFacade();
        $result = $client->get('/sections/' . $modification_id . '/' . $parent_id ?? '');
        return $result;
    }



    private function getLocal($modification_id, $parent_id = null) {
        $this->load->model('fe/tdcatalog/nodes');
        $result = $this->model_fe_tdcatalog_nodes->getByModificationId($modification_id, $parent_id);
        return $result;
    }

}
