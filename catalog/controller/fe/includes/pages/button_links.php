<?php
class ControllerFeIncludesPagesButtonLinks extends Controller {
	public function index() {
        $data = [];
        $data['link_personal'] = $this->url->link('fe/pages/personal', '', true);
        $data['link_requisites'] = $this->url->link('fe/pages/requisites', '', true);
        $data['link_delivery'] = $this->url->link('fe/pages/delivery', '', true);

		return $this->load->view('fe/includes/pages/button_links', $data);
	}
}
