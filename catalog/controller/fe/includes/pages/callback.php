<?php
class ControllerFeIncludesPagesCallback extends Controller {
	public function index($args) {
        $data = [];

        $data['link_redirect'] = $args['link_redirect'];
        $data['action_callback'] = $this->url->link('fe/common/callback', '', true);

		return $this->load->view('fe/includes/pages/callback', $data);
	}
}
