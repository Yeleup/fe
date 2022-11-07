<?php

class ControllerExtensionFeLaximoViewListCategories extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->library('laximo');
    }

    public function index() {
        $data['deps'] = $this->load->controller('extension/fe/laximo/view/deps');
        $data['link_list_categories'] = $this->request->get['url'] ?? '';

        $this->response->setOutput($this->load->view('extension/fe/laximo/list_categories', $data));
    }

}
