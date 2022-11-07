<?php
class ControllerFeCustomerCallback extends Controller {

    public function index() {
        $limit = 12;
        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;
        $orders_count = 0;
        $params = '';

        $this->load->model('fe/customer/callback');
        $items_count = (int)$this->model_fe_customer_callback->get(true)['count'];
        $items = $this->model_fe_customer_callback->get(false, $offset, $limit);

        $data['items'] = $items;

        $pagination = new Pagination();
        $pagination->total = $items_count;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('fe/customer/callback', 'user_token=' . $this->session->data['user_token'] . $params . '&page={page}', true);
        $data['pagination'] = $pagination->render();

        $data['lang_heading_title'] = 'Обратный Звонок';

		$this->document->setTitle($data['lang_heading_title']);

		$data['user_token'] = $this->session->data['user_token'];

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('fe/customer/callback', $data));
    }

}
