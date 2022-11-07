<?php

class ControllerExtensionFeLaximoViewQuickGroup extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
    }

    public function index() {
        $data['deps'] = $this->load->controller('extension/fe/laximo/view/deps');
        $data['link_quick_group'] = $this->request->get['url'] ?? '';

        $this->response->setOutput($this->load->view('extension/fe/laximo/quick_group', $data));
    }

}
