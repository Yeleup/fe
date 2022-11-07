<?php

class ModelExtensionModuleFeNewsInformation extends Model {

    protected $table_info_news = DB_PREFIX . "information_news";
    protected $table_info = DB_PREFIX . "information";
    protected $table_info_desc = DB_PREFIX . "information_description";

    public function getNewsInformations() {
        $sql = "SELECT * FROM {$this->table_info_news} info_news
            JOIN {$this->table_info} info ON info.information_id = info_news.id
            JOIN {$this->table_info_desc} info_desc ON info_news.id = info_desc.information_id
            WHERE
            info.status = '1'";
        $result = $this->db->query($sql);
        return $result->rows;
    }

}
