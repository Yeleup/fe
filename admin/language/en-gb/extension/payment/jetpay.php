<?php
// Heading
$_['heading_title']          = 'Jetpay';
$_['text_edit']              = 'Edit configuration';
$_['text_extension']         = 'Extensions';

$_['entry_status']         = 'Status';

$_['entry_title']      = 'Title';
$_['entry_title_help'] = 'Title which is shown to customer';

$_['entry_description']      = 'Description';
$_['entry_description_help'] = 'Description which is shown to customer';

$_['entry_additional_parameters']      = 'Additional parameters';
$_['entry_additional_parameters_help'] = 'It will be added to redirect link to Jetpay payment page';

$_['entry_projectid']      = 'Project ID';
$_['entry_projectid_help'] = 'Your project ID you could get from Jetpay helpdesk. Leave it blank if test mode';

$_['entry_secretkey']      = 'Secret key';
$_['entry_secretkey_help'] = 'Secret key which is using to sign payment request. You could get it from Jetpay helpdesk';

$_['entry_testmode']      = 'Test mode';
$_['entry_testmode_help'] = 'Test mode.';

$_['entry_popupmode']      = 'Popup mode';
$_['entry_popupmode_help'] = 'Show payment page in popup';

$_['entry_language']      = 'Language';
$_['entry_language_help'] = 'Language of payment page';

$_['entry_currency']      = 'Currency';
$_['entry_currency_help'] = 'Payment currency';

$_['entry_failedstatus']      = 'Failed status';
$_['entry_failedstatus_help'] = 'Specify failed status';

$_['entry_successstatus']      = 'Success status';
$_['entry_successstatus_help'] = 'Specify success status';

$_['entry_pendingstatus']      = 'Pending status';
$_['entry_pendingstatus_help'] = 'Specify pending status';

// Text
$_['text_payment']        = 'Payment';
$_['text_success']        = 'Success: You have modified payment module!';
$_['text_jetpay']      = '<img src="view/image/payment/jetpay.jpg" alt="" title="" style="border: 1px solid #EEEEEE;" />';

$_['entry_geo_zone']         = 'Geo Zone';
$_['entry_sort_order']       = 'Sort Order';

$_['entry_callback_url'] = 'Callback endpoint';
$_['entry_callback_url_help'] = 'You should provide callback endpoint to Jetpay helpdesk. It is required to get information about payment\'s status';

// Error
$_['error_permission']      = 'Warning: You do not have permission to modify the payment module!';
$_['warning_title']      = 'Title is required.';
$_['warning_projectid']      = 'Project id is required without test mode.';
$_['warning_secretkey']      = 'Secret key is required without test mode.';
$_['warning_pendingstatus']      = 'Pending status is required.';
$_['warning_successstatus']      = 'Success status is required.';
$_['warning_failedstatus']      = 'Failed status is required.';
?>
