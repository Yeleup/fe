<?php

class ControllerExtensionModuleFeNews extends Controller {

    public function index() {
    }

    public function install() {
        $table = DB_PREFIX . "information_news";
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
        );";
        $this->db->query($sql);

        $this->load->model('setting/event');
        $this->model_setting_event->addEvent(
            'fe_news_add_check_for_information_after',
            'admin/view/catalog/information_form/after',
            'extension/module/fe_news/addCheckForInformation'
        );
        $this->model_setting_event->addEvent(
            'fe_news_handle_information_model_add_after',
            'admin/model/catalog/information/addInformation/after',
            'extension/module/fe_news/handleInformationModelAdd'
        );
        $this->model_setting_event->addEvent(
            'fe_news_handle_information_model_edit_after',
            'admin/model/catalog/information/editInformation/after',
            'extension/module/fe_news/handleInformationModelEdit'
        );
    }

    public function uninstall() {
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('fe_news_add_check_for_information_after');
        $this->model_setting_event->deleteEventByCode('fe_news_handle_information_model_add_after');
        $this->model_setting_event->deleteEventByCode('fe_news_handle_information_model_edit_after');

    }

    public function addCheckForInformation(&$route, &$args, &$output) {
        $this->load->model('extension/module/fe_news');
        $information_id = $this->request->get['information_id'] ?? null;
        $result_information_news = $this->model_extension_module_fe_news->getById($information_id);
        $is_checked = $result_information_news ? true : false;
        $content = $this->load->view(
            'extension/module/fe_news_add_check_for_information',
            ['is_checked' => $is_checked]
        );

        // Matches and includes $content before </header>
        $pattern = '/(.*)(<\/header>)(.*)/';
        $replacement = '${1}' . $content . '${2}${3}';
        $output = preg_replace($pattern, $replacement, $output);
    }

    public function handleInformationModelAdd(&$route, &$args, &$output) {
        $information_id = $output;
        $add_to_news = ($args[0]['fe_add_to_information_news'] ?? false) === 'on' ? true : false;
        $this->handleInformation($information_id, $add_to_news);
    }

    public function handleInformationModelEdit(&$route, &$args, &$output) {
        $information_id = $args[0];
        $add_to_news = ($args[1]['fe_add_to_information_news'] ?? false) === 'on' ? true : false;
        $this->handleInformation($information_id, $add_to_news);
    }

    public function handleInformation($information_id, $add_to_news) {
        $this->load->model('extension/module/fe_news');
        if ($add_to_news) {
            $this->model_extension_module_fe_news->add($information_id);
        } else {
            $this->model_extension_module_fe_news->delete($information_id);
        }
    }

}
