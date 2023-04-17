<?php

class ControllerExtensionReportFeSearch extends Controller {

    public function log_search(&$route, &$args) {
        $this->load->model('extension/report/fe_search');
        $search_id = $this->model_extension_report_fe_search->addSearch($this->request->get['search'] ?? '');
    }

}
