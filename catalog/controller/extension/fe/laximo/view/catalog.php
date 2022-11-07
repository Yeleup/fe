<?php

class ControllerExtensionFeLaximoViewCatalog extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->library('laximo');
    }

    public function index() {
        $data['deps'] = $this->load->controller('extension/fe/laximo/view/deps');
        $data['link_catalog_list'] = $this->url->link('extension/fe/laximo/api/laximo/listCatalogs', '', true);
        $data['link_quick_group'] = $this->url->link('extension/fe/laximo/view/quick_group', '', true);

        $this->response->setOutput($this->load->view('extension/fe/laximo/catalog', $data));
    }

}
