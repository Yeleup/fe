<?php

class ControllerExtensionModuleFeNews extends Controller {

    public function getNewsList() {
        $this->load->model('extension/module/fe_news_information');
        $informations = $this->model_extension_module_fe_news_information->getNewsInformations();
        $informations = array_map(function($it) {
            $it['description'] = html_entity_decode($it['description'], ENT_QUOTES, 'UTF-8');
            return $it;
        }, $informations);

        $data = [];
        $data['link_information'] = $this->url->link('information/information', '', true) . '&information_id=';
        $data['informations'] = $informations;
        $data['header'] = $this->load->controller('fe/common/header');
        $data['footer'] = $this->load->controller('fe/common/footer');
        return $this->load->view('extension/module/fe_news', $data);
    }

    public function index() {
        $this->response->setOutput($this->getNewsList());
    }

}
