<?php

class ControllerExtensionModuleFeUtm extends Controller {
    protected $table_utm = DB_PREFIX . 'fe_utm';

    public function index() {
        $data['heading_title'] = 'FE UTM';

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/fe_utm', $data));
    }


    public function install() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_utm} (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) UNIQUE NOT NULL,
            count INT(11) DEFAULT 1
        );";
        $this->db->query($sql);

        $this->load->model('setting/event');
        $this->model_setting_event->addEvent('fe_utm_catalog_before', 'catalog/controller/fe/common/header/before', 'extension/module/fe_utm/register');
    }

    public function uninstall() {
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('fe_utm_catalog_before');
    }

}
