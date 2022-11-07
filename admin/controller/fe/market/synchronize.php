<?php
class ControllerFeMarketSynchronize extends Controller {
    public function index() {
        $data['lang_heading_title'] = 'Синхронизация';

		$this->document->setTitle($data['lang_heading_title']);

		$data['user_token'] = $this->session->data['user_token'];

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('fe/market/synchronize', $data));
    }
}
