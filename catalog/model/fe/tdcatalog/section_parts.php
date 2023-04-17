<?php
class ModelFeTdcatalogSectionParts extends Model {

    public function get($modification_id, $section_id) {
        return $this->getTd($modification_id, $section_id);
    }



    private function getTd($modification_id, $section_id) {
        $this->load->library('TdcatalogGuzzleFacade');
        $client = new TdcatalogGuzzleFacade();
        $result = $client->get('/section-parts/' . $modification_id . '/' . $section_id);
        return $result;
    }

}
