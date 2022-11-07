<?php
class ModelFeMarketClientCategory extends Model {

    public function get() {
        $prefix = DB_PREFIX;
        $sql = "SELECT * FROM ${prefix}fe_client_category";
        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function getByGuid($guid) {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_client_category WHERE guid = '" . $this->db->escape($guid) . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function getByRemoteRequisiteGuid($requisite_guid) {
        $requisite_guid = $this->db->escape($requisite_guid);

        $this->load->model('fe/market/http_client');
        $result = $this->model_fe_market_http_client->get("/market/clientCategories/get?guid=$requisite_guid");

        $guid = $result->message->guid ?? null;
        if ($guid && $guid != '00000000-0000-0000-0000-000000000000') {
            $client_category = $this->getByGuid($guid);
        } else {
            $client_category = $this->getByGuid('633aca59-b39d-11eb-832c-8030e02ddc2f'); // Розница
        }
        return $client_category;
    }

    public function add($data) {
        $by_guid = $this->getByGuid($data['guid']);

        if (!$by_guid) {
            $sql = "INSERT INTO " . DB_PREFIX . "fe_client_category SET guid = '" . $this->db->escape($data['guid']) . "', name = '" . $this->db->escape($data['name']) . "'";
            $result = $this->db->query($sql);
            $result = $this->getByGuid($data['guid']);

            return $result['id'];
        } else {
            return $by_guid['id'];
        }

    }

}
