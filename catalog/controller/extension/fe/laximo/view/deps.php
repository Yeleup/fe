<?php

class ControllerExtensionFeLaximoViewDeps extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
    }

    public function index() {
        $data = [];
        $data['footer'] = $this->load->controller('fe/common/footer');
        $data['header'] = $this->load->controller('fe/common/header');

        return $this->load->view('extension/fe/laximo/deps', $data);
    }

}
