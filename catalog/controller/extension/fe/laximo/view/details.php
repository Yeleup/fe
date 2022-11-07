<?php

class ControllerExtensionFeLaximoViewDetails extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->library('laximo');
    }

    public function index() {
        $catalog = $this->request->get['catalog'] ?? '';
        $unit_id = $this->request->get['unit_id'] ?? '';
        $ssd = $this->request->get['ssd'] ?? '';
        $img_url = $this->request->get['img_url'] ?? '';
        $data = [];

        $data['link_details'] = $this->url->link('extension/fe/laximo/api/laximo/ListDetailByUnit', '', true) . "&catalog={$catalog}&unit_id={$unit_id}&ssd={$ssd}";
        $data['link_image'] = $this->url->link('extension/fe/laximo/api/laximo/ListImageMapByUnit', '', true) . "&catalog={$catalog}&unit_id={$unit_id}&ssd={$ssd}";

        $data['deps'] = $this->load->controller('extension/fe/laximo/view/deps');
        $data['image_url'] = str_replace('%size%', 'source', urldecode($img_url));

        $this->response->setOutput($this->load->view('extension/fe/laximo/details', $data));
    }

}
