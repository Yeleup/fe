<?php
class ControllerFePagesLogs extends Controller {

    public function index() {
        $page = (int)($this->request->get['page'] ?? 1);
        $offset = $page - 1;
        $limit = $this->config->get('config_limit_admin');
        $url = '';

        $filter = [];

        $filter_code = $this->request->get['filter_code'] ?? '';
        if ($filter_code) $filter['code'] = $filter_code;
        $url = $filter_code ? '&filter_code=' . $filter_code : '';

        $filter_date_start = $this->request->get['filter_date_start'] ?? null;
        if ($filter_date_start) $filter['date_start'] = $filter_date_start;
        $url = $filter_date_start ? '&filter_date_start=' . $filter_date_start : '';

        $filter_date_end = $this->request->get['filter_date_end'] ?? null;
        if ($filter_date_end) $filter['date_end'] = $filter_date_end;
        $url = $filter_date_end ? '&filter_date_end=' . $filter_date_end : '';

        $filter['limit'] = $limit;
        $filter['offset'] = $offset * $limit;

        $this->load->model('fe/util/log');
        $logs_count = $this->model_fe_util_log->getLogsCount($filter);
        $logs = $this->model_fe_util_log->getLogs($filter);

        $user_token = $this->session->data['user_token'];

        $pagination = new Pagination();
        $pagination->total = $logs_count;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('fe/pages/logs', 'user_token=' . $user_token . $url . '&page={page}', true);
        $data['pagination'] = $pagination->render();

        $data['logs'] = $logs;

        $data['lang_heading_title'] = 'Logs';
		$this->document->setTitle($data['lang_heading_title']);

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;
        $data['filter_code'] = $filter_code;
		$data['user_token'] = $user_token;

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('pages/banner');

        $this->response->setOutput($this->load->view('fe/pages/logs', $data));
    }

}
