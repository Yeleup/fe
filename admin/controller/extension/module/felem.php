<?php
class ControllerExtensionModuleFelem extends Controller {
    protected $CONFIG_ADMIN_GROUP_ID = 1;

    public function index()
    {
        $this->response->setOutput("FE Module");
    }

    public function validate()
    {

    }

    public function install()
    {
        // EVENTS
        $this->load->model('setting/event');
        // $this->model_setting_event->addEvent('fe_test_test_after', 'admin/model/catalog/product/getProducts/after', 'fe/event/test/test/after');
        // $this->model_setting_event->addEvent('fe_test_test_before', 'admin/model/catalog/product/getProducts/before', 'fe/event/test/test/before');

        // Admin Controller
        $this->model_setting_event->addEvent('fe_admin_controller_common_header_after', 'admin/controller/common/header/after', 'fe/event/controller/common/header/after');

        // Admin Model
        $this->model_setting_event->addEvent('fe_admin_model_customer_customer_edit_customer_after', 'admin/model/customer/customer/editCustomer/after', 'fe/event/model/customer/customer_edit_customer/after');
        $this->model_setting_event->addEvent('fe_admin_model_customer_customer_edit_customer_before', 'admin/model/customer/customer/editCustomer/before', 'fe/event/model/customer/customer_edit_customer/before');

        // Admin View
        $this->model_setting_event->addEvent('fe_admin_view_catalog_product_form_after', 'admin/view/catalog/product_form/after', 'fe/event/view/catalog/product_form/after');
        $this->model_setting_event->addEvent('fe_admin_view_catalog_product_form_before', 'admin/view/catalog/product_form/before', 'fe/event/view/catalog/product_form/before');

        $this->model_setting_event->addEvent('fe_admin_view_common_column_left_after', 'admin/view/common/column_left/after', 'fe/event/view/common/column_left/after');
        $this->model_setting_event->addEvent('fe_admin_view_common_column_left_before', 'admin/view/common/column_left/before', 'fe/event/view/common/column_left/before');

        $this->model_setting_event->addEvent('fe_admin_view_customer_customer_form_after', 'admin/view/customer/customer_form/after', 'fe/event/view/customer/customer_form/after');
        $this->model_setting_event->addEvent('fe_admin_view_customer_customer_form_before', 'admin/view/customer/customer_form/before', 'fe/event/view/customer/customer_form/before');

        $this->model_setting_event->addEvent('fe_admin_view_sale_order_info_after', 'admin/view/sale/order_info/after', 'fe/event/view/sale/order_info/after');
        $this->model_setting_event->addEvent('fe_admin_view_sale_order_info_before', 'admin/view/sale/order_info/before', 'fe/event/view/sale/order_info/before');

        // Catalog Controller

        $this->model_setting_event->addEvent('fe_catalog_controller_account_account_after', 'catalog/controller/account/account/after', 'fe/event/controller/account/account/after');
        $this->model_setting_event->addEvent('fe_catalog_controller_account_account_before', 'catalog/controller/account/account/before', 'fe/event/controller/account/account/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_account_login_after', 'catalog/controller/account/login/after', 'fe/event/controller/account/login/after');
        $this->model_setting_event->addEvent('fe_catalog_controller_account_login_before', 'catalog/controller/account/login/before', 'fe/event/controller/account/login/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_account_logout_after', 'catalog/controller/account/logout/after', 'fe/event/controller/account/logout/after');
        $this->model_setting_event->addEvent('fe_catalog_controller_account_logout_before', 'catalog/controller/account/logout/before', 'fe/event/controller/account/logout/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_account_password_after', 'catalog/controller/account/password/after', 'fe/event/controller/account/password/after');
        $this->model_setting_event->addEvent('fe_catalog_controller_account_password_before', 'catalog/controller/account/password/before', 'fe/event/controller/account/password/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_api_order_update_status_before', 'catalog/controller/api/order/update_status/before', 'fe/event/controller/api/order/update_status/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_checkout_failure_before', 'catalog/controller/checkout/failure/before', 'fe/event/controller/checkout/failure/before');
        $this->model_setting_event->addEvent('fe_catalog_controller_checkout_success_before', 'catalog/controller/checkout/success/before', 'fe/event/controller/checkout/success/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_common_header_after', 'catalog/controller/common/header/after', 'fe/event/controller/common/header/after');
        $this->model_setting_event->addEvent('fe_catalog_controller_common_header_before', 'catalog/controller/common/header/before', 'fe/event/controller/common/header/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_common_footer_after', 'catalog/controller/common/footer/after', 'fe/event/controller/common/footer/after');
        $this->model_setting_event->addEvent('fe_catalog_controller_common_footer_before', 'catalog/controller/common/footer/before', 'fe/event/controller/common/footer/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_common_home_after', 'catalog/controller/common/home/after', 'fe/event/controller/common/home/after');
        $this->model_setting_event->addEvent('fe_catalog_controller_common_home_before', 'catalog/controller/common/home/before', 'fe/event/controller/common/home/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_product_product_after', 'catalog/controller/product/product/after', 'fe/event/controller/product/product/after');
        $this->model_setting_event->addEvent('fe_catalog_controller_product_product_before', 'catalog/controller/product/product/before', 'fe/event/controller/product/product/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_account_reset_after', 'catalog/controller/account/reset/after', 'fe/event/controller/account/reset/after');
        $this->model_setting_event->addEvent('fe_catalog_controller_account_reset_before', 'catalog/controller/account/reset/before', 'fe/event/controller/account/reset/before');

        $this->model_setting_event->addEvent('fe_catalog_controller_account_forgotten_after', 'catalog/controller/account/forgotten/after', 'fe/event/controller/account/forgotten/after');
        $this->model_setting_event->addEvent('fe_catalog_controller_account_forgotten_before', 'catalog/controller/account/forgotten/before', 'fe/event/controller/account/forgotten/before');

        $this->model_setting_event->addEvent('fe_catalog_view_account_reset_after', 'catalog/view/account/reset/after', 'fe/event/view/account/reset/after');
        $this->model_setting_event->addEvent('fe_catalog_view_account_reset_before', 'catalog/view/account/reset/before', 'fe/event/view/account/reset/before');

        $this->model_setting_event->addEvent('fe_catalog_view_error_not_found_after', 'catalog/view/error/not_found/after', 'fe/event/view/error/not_found/after');
        $this->model_setting_event->addEvent('fe_catalog_view_error_not_found_before', 'catalog/view/error/not_found/before', 'fe/event/view/error/not_found/before');

        // USER PERMISSIONS
        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->CONFIG_ADMIN_GROUP_ID, 'access', 'fe/api/laximo_starter');
        $this->model_user_user_group->addPermission($this->CONFIG_ADMIN_GROUP_ID, 'access', 'fe/api/market_starter');
        $this->model_user_user_group->addPermission($this->CONFIG_ADMIN_GROUP_ID, 'access', 'fe/market/synchronize');

        // CUSTOMER REG TYPE AND STATUS
        $this->load->model('fe/customer/reg_type');
        $this->model_fe_customer_reg_type->add([
            'id' => 1,
            'name' => 'wholesale',
            'name_display' => 'Оптовый',
        ]);
        $this->model_fe_customer_reg_type->add([
            'id' => 2,
            'name' => 'retail',
            'name_display' => 'Розничный',
        ]);

        $this->load->model('fe/customer/status');
        $this->model_fe_customer_status->add([
            'id' => 1,
            'name' => 'physical',
            'name_display' => 'Физическое Лицо',
        ]);
        $this->model_fe_customer_status->add([
            'id' => 2,
            'name' => 'legal',
            'name_display' => 'Юридическое Лицо',
        ]);

        // ORDER STATUS
        $this->load->model('fe/checkout/fe_order_status');
        $this->model_fe_checkout_fe_order_status->add([
            'id' => 1,
            'code' => 'pending',
            'display' => 'Ожидается'
        ]);
        $this->model_fe_checkout_fe_order_status->add([
            'id' => 2,
            'code' => 'completed',
            'display' => 'Завершен'
        ]);

        // ADD KZT CURRENCY
        $this->load->model('localisation/currency');
        $currency_kzt = $this->model_localisation_currency->getCurrencyByCode('KZT');
        if (!$currency_kzt) {
            $this->model_localisation_currency->addCurrency([
                'title' => 'Kazakhstani Tenge',
                'code' => 'KZT',
                'symbol_left' => '',
                'symbol_right' => '₸',
                'decimal_place' => '2',
                // 'value' => '434.45868',
                'value' => '1.00',
                'status' => 1
            ]);
        }

        // Add Customer Type
        $this->load->model('fe/customer/customer_type');
        $this->model_fe_customer_customer_type->add([
            'name' => 'business',
            'display' => 'Business'
        ]);
        $this->model_fe_customer_customer_type->add([
            'name' => 'personal',
            'display' => 'Personal'
        ]);
    }

    public function uninstall()
    {
        // EVENTS
        $this->load->model('setting/event');
        // $this->model_setting_event->deleteEventByCode('fe_test_test_after');
        // $this->model_setting_event->deleteEventByCode('fe_test_test_before');

        // Admin Controller
        $this->model_setting_event->deleteEventByCode('fe_admin_controller_common_header_after');

        // Admin Model
        $this->model_setting_event->deleteEventByCode('fe_admin_model_customer_customer_edit_customer_after');
        $this->model_setting_event->deleteEventByCode('fe_admin_model_customer_customer_edit_customer_before');

        // Admin View
        $this->model_setting_event->deleteEventByCode('fe_admin_view_catalog_product_form_after');
        $this->model_setting_event->deleteEventByCode('fe_admin_view_catalog_product_form_before');

        $this->model_setting_event->deleteEventByCode('fe_admin_view_common_column_left_after');
        $this->model_setting_event->deleteEventByCode('fe_admin_view_common_column_left_before');

        $this->model_setting_event->deleteEventByCode('fe_admin_view_customer_customer_form_after');
        $this->model_setting_event->deleteEventByCode('fe_admin_view_customer_customer_form_before');

        $this->model_setting_event->deleteEventByCode('fe_admin_view_sale_order_info_after');
        $this->model_setting_event->deleteEventByCode('fe_admin_view_sale_order_info_before');

        // Catalog Controller
        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_account_after');
        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_account_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_login_after');
        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_login_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_logout_after');
        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_logout_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_password_after');
        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_password_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_api_order_update_status_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_checkout_failure_before');
        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_checkout_success_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_common_home_after');
        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_common_home_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_product_product_after');
        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_product_product_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_reset_after');
        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_reset_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_forgotten_after');
        $this->model_setting_event->deleteEventByCode('fe_catalog_controller_account_forgotten_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_view_account_reset_after');
        $this->model_setting_event->deleteEventByCode('fe_catalog_view_account_reset_before');

        $this->model_setting_event->deleteEventByCode('fe_catalog_view_error_not_found_after');
        $this->model_setting_event->deleteEventByCode('fe_catalog_view_error_not_found_before');

        // USER PERMISSIONS
        $this->load->model('user/user_group');

        $this->model_user_user_group->removePermission($this->CONFIG_ADMIN_GROUP_ID, 'access', 'fe/api/laximo_starter');
        $this->model_user_user_group->removePermission($this->CONFIG_ADMIN_GROUP_ID, 'access', 'fe/api/market_starter');
        $this->model_user_user_group->removePermission($this->CONFIG_ADMIN_GROUP_ID, 'access', 'fe/market/synchronize');

        // CUSTOMER REG TYPE AND STATUS
        $this->load->model('fe/customer/reg_type');
        $this->model_fe_customer_reg_type->deleteByName('wholesale');
        $this->model_fe_customer_reg_type->deleteByName('retail');
        $this->load->model('fe/customer/status');
        $this->model_fe_customer_status->deleteByName('physical');
        $this->model_fe_customer_status->deleteByName('legal');

        // ORDER STATUS
        $this->load->model('fe/checkout/fe_order_status');
        $this->model_fe_checkout_fe_order_status->deleteAll();
    }
}
