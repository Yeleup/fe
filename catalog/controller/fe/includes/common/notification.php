<?php
class ControllerFeIncludesCommonNotification extends Controller {
	public function index($args) {
        $data = [];
        $data['notifications'] = [];

        if ($args['notifications'] ?? false) {
            array_push($data['notifications'], ...$args['notifications']);
        }

		return $this->load->view('fe/includes/common/notification', $data);
	}
}
