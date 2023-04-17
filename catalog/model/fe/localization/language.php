<?php
class ModelFeLocalizationLanguage extends Model {

    public function getCurrentId() {
        $code = $this->language->get('code');
        $sql = "SELECT * FROM `" . DB_PREFIX . "language` WHERE code = 'ru-ru'";
        $result = $this->db->query($sql);
        return $result->row ? $result->row['language_id'] : false;
    }

}
