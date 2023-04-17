<?php
class ControllerFeEventViewCustomerCustomerForm extends Controller {

    public function before(&$route, &$args) {
    }



	public function after(&$route, &$args, &$output) {
        if ($args['customer_id']) {
            $this->load->model('fe/customer/fe_customer');
            $fe_customer = $this->model_fe_customer_fe_customer->getById($args['customer_id']);

            if (!$fe_customer) {
                return;
            }

            $this->load->model('fe/customer/reg_type');
            $reg_type_options = $this->model_fe_customer_reg_type->get();

            $this->load->model('fe/customer/status');
            $status_options = $this->model_fe_customer_status->get();

            $this->load->model('fe/market/client_category');
            $client_category_options = $this->model_fe_market_client_category->get();

            $this->load->model('fe/customer/customer_type');
            $customer_type_options = $this->model_fe_customer_customer_type->getAll();

            $this->load->model('fe/market/responsible');
            $responsibles_options = $this->model_fe_market_responsible->getAll();

            // Nav Tabs
            $search_str = '#<ul class="nav nav-tabs">[\s\S]*?</ul>#m';
            $insert_str = '
            <li>
            <a href="#tab-fe-customer" data-toggle="tab">
            Доп. Информация
            </a>
            </li>
            ';
            preg_match($search_str, $output, $matches, PREG_OFFSET_CAPTURE);
            if ($matches) {
                $pos = strlen($matches[0][0]) + $matches[0][1] - 5;
                $output = substr($output, 0, $pos) . $insert_str . substr($output, $pos);
            }

            // Tab
            $data = [];
            $data['fe_customer'] = $fe_customer;
            $data['reg_type_options'] = $reg_type_options;
            $data['status_options'] = $status_options;
            $data['customer_type_options'] = $customer_type_options;
            $data['client_category_options'] = $client_category_options;
            $data['user_token'] = $this->session->data['user_token'];
            $data['responsibles_options'] = $responsibles_options;
            $data['link_uuid_generate'] = $this->url->link('fe/api/util/uuid_generate', '', true) . "&user_token=${data['user_token']}";

            $this->load->model('fe/customer/requisite');
            $data['requisites'] = $this->model_fe_customer_requisite->getByCustomerId($args['customer_id']);

            $search_str = '#<div class="tab-content">[\s\S]*?</form>#';
            $insert_str = $this->load->view('fe/event/view/customer/customer_form/fe_customer_fieldset', $data);
            preg_match($search_str, $output, $matches, PREG_OFFSET_CAPTURE);
            if ($matches) {
                $search_str = '#</div>[\s]*?</form>#';
                $pos = $matches[0][1];
                preg_match($search_str, $matches[0][0], $matches, PREG_OFFSET_CAPTURE);
                if ($matches) {
                    $pos += $matches[0][1];
                    $output = substr($output, 0, $pos) . $insert_str . substr($output, $pos);
                }
            }
        }
    }

}
