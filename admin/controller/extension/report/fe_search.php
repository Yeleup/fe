<?php

class ControllerExtensionReportFeSearch extends Controller {

    public function index() {
        $this->load->language('extension/report/fe_search');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('report_fe_search', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/report/fe_search', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/report/fe_search', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true);

        if (isset($this->request->post['report_fe_search_status'])) {
            $data['report_fe_search_status'] = $this->request->post['report_fe_search_status'];
        } else {
            $data['report_fe_search_status'] = $this->config->get('report_fe_search_status');
        }

        if (isset($this->request->post['report_fe_search_sort_order'])) {
            $data['report_fe_search_sort_order'] = $this->request->post['report_fe_search_sort_order'];
        } else {
            $data['report_fe_search_sort_order'] = $this->config->get('report_fe_search_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/report/fe_search_form', $data));
    }

    protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/report/fe_search')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

    public function exportExcel() {
        $this->load->library('xlsxwriter');

        $this->load->language('extension/report/fe_search');
        $filter_date_start = $this->request->get['filter_date_start'] ?? null;
        $filter_date_end = $this->request->get['filter_date_end'] ?? null;
        $filter = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_limit' => 100000,
            'filter_offset' => 0
        ];

        $this->load->model('extension/report/fe_search');
        $searches_result = $this->model_extension_report_fe_search->getSearches($filter);

        $filename = "search_report.xlsx";
        header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $rows = [];
        foreach ($searches_result->rows as $search) {
            $rows[] = [(string)$search['search'], (string)$search['amount']];
        }

        $writer = new XLSXWriter();
        foreach ($rows as $row) {
            $writer->writeSheetRow('Search', $row);
        }

        $this->response->setOutput($writer->writeToString());
    }

    public function report() {
        $this->load->language('extension/report/fe_search');

        $filter_date_start = $this->request->get['filter_date_start'] ?? null;
        $filter_date_end = $this->request->get['filter_date_end'] ?? null;
        $page = (int)($this->request->get['page'] ?? 1);
        $offset = $page - 1;
        $limit = $this->config->get('config_limit_admin');

        $url = '';
        if ($filter_date_start)
            $url .= "&filter_date_start=$filter_date_start";

        if ($filter_date_end)
            $url .= "&filter_date_end=$filter_date_end";

        $this->load->model('extension/report/fe_search');

        $filter = [
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            'filter_limit' => $limit,
            'filter_offset' => $offset * $limit
        ];

        $searches_total = $this->model_extension_report_fe_search->getTotalSearches($filter);
        $searches = $this->model_extension_report_fe_search->getSearches($filter);

        $data['searches'] = $searches->rows;


        $pagination = new Pagination();
        $pagination->total = $searches_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('report/report', 'user_token=' . $this->session->data['user_token'] . '&code=fe_search' . $url . '&page={page}', true);
        $data['pagination'] = $pagination->render();

        $data['filter_date_start'] = $filter_date_start;
        $data['filter_date_end'] = $filter_date_end;

        $data['link_export_excel'] = $this->url->link('extension/report/fe_search/exportExcel', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['user_token'] = $this->session->data['user_token'];

        return $this->load->view('extension/report/fe_search_info', $data);
    }

    public function install() {
        $prefix = DB_PREFIX;

        $this->db->query("CREATE TABLE IF NOT EXISTS {$prefix}fe_search (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            search VARCHAR(255),
            created_at TIMESTAMP DEFAULT NOW()
        )");

        $this->load->model('setting/event');

        $this->model_setting_event->addEvent('fe_report_search_log_before', 'catalog/controller/fe/api/common/home_search/before', 'extension/report/fe_search/log_search');
    }

    public function uninstall() {
        $this->load->model('setting/event');

        $this->model_setting_event->deleteEventByCode('fe_report_search_log_before');

    }

}
