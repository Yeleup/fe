<?php
class ControllerFePagesBanner extends Controller {

    public function index() {
        $this->load->model('fe/util/util');
        $util_banner_name = 'home_banner';

        if (isset($this->request->post['banner_text'])) {
            $this->model_fe_util_util->upsert($util_banner_name, 0, $this->request->post['banner_text']);
        }

        $data['banner'] = $this->model_fe_util_util->getByName($util_banner_name);
        $data['banner_text'] = $data['banner']['char_val'] ?? '';

        $data['lang_heading_title'] = 'Баннер';

		$this->document->setTitle($data['lang_heading_title']);

		$data['user_token'] = $this->session->data['user_token'];
        $data['action'] = $this->url->link('fe/pages/banner', "user_token=${data['user_token']}", true);

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('pages/banner');

        $this->response->setOutput($this->load->view('fe/pages/banner', $data));
    }

}
