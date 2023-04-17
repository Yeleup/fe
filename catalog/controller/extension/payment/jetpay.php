<?php

require_once(__DIR__ . '/../../../model/extension/payment/jetpay/common/JetpayOrderIdFormatter.php');

class ControllerExtensionPaymentJetpay extends Controller
{

    public function index()
    {
        $this->language->load('extension/payment/jetpay');
        $this->load->model('checkout/order');

        $data['action'] = $this->generateRedirectUrl();
        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['popup_mode'] = !empty($this->config->get('payment_jetpay_popupmode'));
        $data['paymentpage_host'] = $this->getPaymentPageHost();

        return $this->load->view('extension/payment/jetpay', $data);
    }

    public function confirm() {
        if ($this->session->data['payment_method']['code'] === 'jetpay') {
            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory(
                $this->session->data['order_id'], $this->config->get('payment_jetpay_pendingstatus'),
                'Jetpay: pending',
                true
            );
            die('ok');
        }
        http_response_code(400);
        die('fail');
    }

    /**
     * @param $currencyCode
     * @return Signer
     */
    protected function getSigner($currencyCode)
    {
        return new Signer(
            $this->config->get('payment_jetpay_testmode'),
            $this->config->get('payment_jetpay_projectid'),
            $this->config->get('payment_jetpay_secretkey'),
            $this->config->get('payment_jetpay_language'),
            $currencyCode,
            $this->config->get('payment_jetpay_additional_parameters'),
            $this->url
        );
    }

    /**
     * @return string
     */
    public function generateRedirectUrl()
    {
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $orderAmount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
        // $orderAmount = (float)$orderAmount * pow(10, (int)$this->currency->getDecimalPlace($order_info['currency_code']));
        $orderAmount = (float)$orderAmount * pow(10, 2);
        $orderAmount = intval(strval($orderAmount));

        $signer = $this->getSigner($order_info['currency_code']);

        return sprintf(
            'https://%s/payment?%s',
            $this->getPaymentPageHost(),
            $signer->getQueryString($order_info['order_id'], $orderAmount, $order_info['customer_id'])
        );
    }

    /**
     * @return bool
     */
    private function isInTestMode()
    {
        return !empty($this->config->get('payment_jetpay_testmode'));
    }

    /**
     * @return string|null
     */
    private function getOrderId()
    {
        if (!empty($this->request->get['order_id'])) {
            return $this->request->get['order_id'];
        }
        return null;
    }

    /**
     * @return bool
     */
    private function isTestRequest()
    {
        return
            !empty($this->request->get['test'])
            &&
            $this->request->get['test'] === '1';
    }

    public function success_callback()
    {
        $orderId = $this->getOrderId();
        $isTestMode = $this->isInTestMode();
        $isTestRequest = $this->isTestRequest();

        $order_info = null;

        if ($orderId) {
            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($orderId);
        }

        if (!$order_info) {
            die('Order not found');
        }

        if ($isTestMode && $isTestRequest) {
            $this
                ->model_checkout_order
                ->addOrderHistory($orderId, $this->config->get('payment_jetpay_successstatus'), "Jetpay: test payment processed", true);

            $this->load->controller('fe/checkout/checkout/completeOrder', ['order_id' => $orderId]);
        }

        $this->response->redirect($this->url->link('checkout/success', '', true));
    }

    public function fail_callback()
    {
        $logger = new Log('jetpay_fail.log');
        $body = file_get_contents('php://input');
        $logger->write(sprintf('%s: %s', 'fail callback', $body));

        $orderId = $this->getOrderId();
        $isTestMode = $this->isInTestMode();
        $isTestRequest = $this->isTestRequest();

        $order_info = null;

        if ($orderId) {
            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($orderId);
        }

        if (!$order_info) {
            die('Order not found');
        }

        $status = $this->config->get('payment_jetpay_failedstatus');

        if ($isTestMode && $isTestRequest) {
            $this
                ->model_checkout_order
                ->addOrderHistory($orderId, $status, 'Jetpay: test payment failed', true);
        } else {
            $this
                ->model_checkout_order
                ->addOrderHistory($orderId, $status, 'Jetpay: payment failed', true);
        }

        $this->response->redirect($this->url->link('checkout/failure', '', true));
    }

    public function callback()
    {
        $logger = new Log('jetpay.log');

        $body = file_get_contents('php://input');
        $bodyData = json_decode($body, true);

        $this->load->model('fe/util/log');
        $this->model_fe_util_log->log('jetpay_body', $body);
        $this->model_fe_util_log->log('jetpay_body_data', json_encode($bodyData));

        $signer = $this->getSigner(null);

        if (is_array($bodyData) && $signer->checkSignature($bodyData)) {
            $logger->write(sprintf('%s: %s', 'signature valid', print_r($bodyData['signature'], true)));

            $orderId = $bodyData['payment']['id'];
            $orderId = JetpayOrderIdFormatter::removeOrderPrefix($orderId, Signer::CMS_PREFIX);

            $paymentStatus = $bodyData['payment']['status'];

            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($orderId);
            if (!$order_info) {
                http_response_code(404);
                die('order not found');
            }

            $state = 'success' === $paymentStatus ?
                $this->config->get('payment_jetpay_successstatus')
                :
                $this->config->get('payment_jetpay_failedstatus');
            $message = 'success' === $paymentStatus ?
                'Jetpay: payment processed'
                :
                'Jetpay: payment failed';

            $this
                ->model_checkout_order
                ->addOrderHistory($orderId, $state, $message, true);

            if ($paymentStatus === 'success') {
                $this->load->controller('fe/checkout/checkout/completeOrder', ['order_id' => $orderId]);
            }

            die('ok');
        }

        $logger->write('signature invalid');

        http_response_code(400);
        die('signature invalid');
    }

    /**
     * @return string
     */
    private function getPaymentPageHost()
    {
        $host = getenv('PAYMENTPAGE_HOST');

        return is_string($host) ? $host : 'paymentpage.jetpay.kz';
    }

    private function log($variable, $data = null)
    {
        $this->log->write(sprintf('%s: %s', $variable, print_r($data, true)));
    }
}

class Signer
{
    /**
     * @var bool
     */
    protected $isTest;

    /**
     * @var string|int
     */
    protected $projectId;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $ppLanguage;

    /**
     * @var string
     */
    protected $ppCurrency;

    /**
     * @var string
     */
    protected $additionalParameters;

    /**
     * @var string
     */
    protected $url;

    const JETPAY_TEST_PROJECT_ID = 112;
    const JETPAY_TEST_SECRET_KEY = 'kHRhsQHHhOUHeD+rt4kgH7OZiwE=';

    const INTERFACE_TYPE = 15;

    const CMS_PREFIX = 'oc3';

    /**
     * Signer constructor.
     * @param $isTest
     * @param $projectId
     * @param $secretKey
     * @param $ppLanguage
     * @param $ppCurrency
     * @param $additionalParameters
     * @param $url
     */
    public function __construct($isTest, $projectId, $secretKey, $ppLanguage, $ppCurrency, $additionalParameters, $url)
    {
        $this->isTest = $isTest;
        $this->projectId = $projectId;
        $this->secretKey = $secretKey;
        $this->ppLanguage = $ppLanguage;
        $this->ppCurrency = $ppCurrency;
        $this->additionalParameters = $additionalParameters;
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $isTest = $this->isTest;
        $projectId = $this->projectId;
        $salt = $this->secretKey;
        $ppCurrency = $this->ppCurrency;
        $ppLanguage = $this->ppLanguage;
        $additionalParameters = $this->additionalParameters;

        return compact(
            'isTest',
            'projectId',
            'salt',
            'ppCurrency',
            'ppLanguage',
            'additionalParameters'
        );
    }

    /**
     * @return string
     */
    public function getQueryString($orderId, $paymentAmount, $customerId)
    {
        $config = $this->getConfig();

        $successUrl = $this->getSuccessUrl($orderId, $config);
        $failUrl = $this->getFailUrl($orderId, $config);

        $urlData = $this->createUrlData($orderId, $paymentAmount, $customerId, $config, $successUrl, $failUrl);

        if (!empty($config['additionalParameters'])) {
            $config['additionalParameters'] = html_entity_decode($config['additionalParameters']);
            $additionalData = [];
            parse_str($config['additionalParameters'], $additionalData);
            $urlData = array_merge($urlData, $additionalData);
        }

        $urlData['signature'] = $this->signData($urlData, []);

        return http_build_query($urlData, '', '&');
    }

    /**
     * @param $orderId
     * @param array $config
     * @param $successUrl
     * @param $failUrl
     * @return array
     */
    protected function createUrlData($orderId, $paymentAmount, $customerId, array $config, $successUrl, $failUrl)
    {
        $paymentId = $orderId;
        if ($config['isTest']) {
            $paymentId = JetpayOrderIdFormatter::addOrderPrefix($paymentId, self::CMS_PREFIX);
        }
        $urlParams = [
            'project_id' => $config['isTest'] ? self::JETPAY_TEST_PROJECT_ID : $config['projectId'],
            'payment_amount' => $paymentAmount,
            'payment_id' => $paymentId,
            'payment_currency' => $config['ppCurrency'],
            'language_code' => strtolower($config['ppLanguage']),
            'merchant_success_url' => $successUrl,
            'merchant_fail_url' => $failUrl,
            'interface_type' => json_encode($this->getInterfaceType()),
        ];
        if ($customerId) {
            $urlParams['customer_id'] = $customerId;
        }
        return $urlParams;
    }

    /**
     * @return array
     */
    private function getInterfaceType()
    {
        return [
            'id' => self::INTERFACE_TYPE,
        ];
    }

    /**
     * @param $orderId
     * @param array $config
     * @return string
     */
    private function getSuccessUrl($orderId, array $config) {
        $prefix = $this->url->link('extension/payment/jetpay/success_callback', '', 'SSL');
        if ($config['isTest']) {
            return $prefix . '&' . http_build_query([
                    'order_id' => $orderId,
                    'test' => 1
                ]);
        }
        return $prefix . '&' . http_build_query(['order_id' => $orderId]);
    }

    /**
     * @param $orderId
     * @param array $config
     * @return string
     */
    private function getFailUrl($orderId, array $config) {

        $prefix = $this->url->link('extension/payment/jetpay/fail_callback', '', 'SSL');
        if ($config['isTest']) {
            return $prefix . '&' . http_build_query([
                    'order_id' => $orderId,
                    'test' => 1
                ]);
        }
        return $prefix . '&' . http_build_query(['order_id' => $orderId]);
    }


    /**
     * Get parameters to sign
     * @param array $params
     * @param array $ignoreParamKeys
     * @param int $currentLevel
     * @param string $prefix
     * @return array
     */
    private function getParamsToSign(
        array $params,
        array $ignoreParamKeys = [],
        $currentLevel = 1,
        $prefix = ''
    )
    {
        $paramsToSign = [];
        foreach ($params as $key => $value) {
            if ((in_array($key, $ignoreParamKeys) && $currentLevel == 1)) {
                continue;
            }
            $paramKey = ($prefix ? $prefix . ':' : '') . $key;
            if (is_array($value)) {
                if ($currentLevel >= 3) {
                    $paramsToSign[$paramKey] = (string)$paramKey.':';
                } else {
                    $subArray = self::getParamsToSign($value, $ignoreParamKeys, $currentLevel + 1, $paramKey);
                    $paramsToSign = array_merge($paramsToSign, $subArray);
                }
            } else {
                if (is_bool($value)) {
                    $value = $value ? '1' : '0';
                } else {
                    $value = (string)$value;
                }
                $paramsToSign[$paramKey] = (string)$paramKey.':'.$value;
            }
        }
        if ($currentLevel == 1) {
            ksort($paramsToSign, SORT_NATURAL);
        }
        return $paramsToSign;
    }

    private function signData(array $data, $skipParams) {
        $config = $this->getConfig();
        $paramsToSign = $this->getParamsToSign($data, $skipParams);
        $stringToSign = $this->getStringToSign($paramsToSign);
        $secretKey = $config['isTest'] ? self::JETPAY_TEST_SECRET_KEY : $config['salt'];
        return base64_encode(hash_hmac('sha512', $stringToSign, $secretKey, true));
    }

    private function getStringToSign(array $paramsToSign)
    {
        return implode(';', $paramsToSign);
    }

    public function checkSignature(array $data) {
        $signature = $data['signature'];
        unset($data['signature']);
        return $this->signData($data, []) === $signature;
    }
}
