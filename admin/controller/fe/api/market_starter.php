<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class ControllerFeApiMarketStarter extends Controller {
    protected $json = [
        'code' => 200,
        'message' => 'Синхронизировано.',
    ];

    public function index() {
        $this->response->setOutput("Starter");
    }

    public function all()
    {
        $result = $this->load->controller('fe/api/market/category');
        $result = $this->load->controller('fe/api/market/brand');
        $result = $this->load->controller('fe/api/market/product');
        $result = $this->load->controller('fe/api/market/price_for');
        $result = $this->load->controller('fe/api/market/client_category');
        $result = $this->load->controller('fe/api/market/price');
        $result = $this->load->controller('fe/api/market/prices_by_category');
        // $result = $this->load->controller('fe/api/market/balance');
        $result = $this->load->controller('fe/api/market/crosscode');
        $this->getResponse($result);
    }

    public function cleanup()
    {
        $result = $this->load->controller('fe/api/market/cleanup');
        $this->getResponse($result);
    }

    public function category() {
        $result = $this->load->controller('fe/api/market/category');
        $this->getResponse($result);
    }

    public function brand()
    {
        $result = $this->load->controller('fe/api/market/brand');
        $this->getResponse($result);
    }

    public function product()
    {
        $result = $this->load->controller('fe/api/market/product');
        $this->getResponse($result);
    }

    public function productOne()
    {
        $product_id = $this->request->get['product_id'];
        $result = $this->load->controller('fe/api/market/balance/one', $product_id);
        // $result = $this->load->controller('fe/api/market/product', [
        //     'product_id' => $product_id
        // ]);
        $this->json['result'] = $result;
        $this->getResponse($result);
    }

    public function productImages()
    {
        $result = $this->load->controller('fe/api/market/product_images');
        $this->getResponse($result);
    }

    public function priceAll()
    {
        $result = $this->load->controller('fe/api/market/client_category');
        $result = $this->load->controller('fe/api/market/price_for');
        $result = $this->load->controller('fe/api/market/price');
        $result = $this->load->controller('fe/api/market/prices_by_category');
        $result = $this->load->controller('fe/api/market/responsibles');
        $this->getResponse($result);
    }

    public function price()
    {
        $result = $this->load->controller('fe/api/market/price');
        $this->getResponse($result);
    }

    public function priceFor()
    {
        $result = $this->load->controller('fe/api/market/price_for');
        $this->getResponse($result);
    }

    public function clientCategory()
    {
        $result = $this->load->controller('fe/api/market/client_category');
        $this->getResponse($result);
    }

    public function updateClientCategory()
    {
        $result = $this->load->controller('fe/api/market/update_client_category');
        $this->getResponse($result);
    }

    public function pricesByCategory()
    {
        $result = $this->load->controller('fe/api/market/prices_by_category');
        $this->getResponse($result);
    }

    public function balance()
    {
        $result = $this->load->controller('fe/api/market/balance');
        $this->getResponse($result);
    }

    public function balanceOne()
    {
        $result = false;
        if (isset($this->request->get['product_id'])) {
            $result = $this->load->controller('fe/api/market/balance/one', $this->request->get['product_id']);
        }
        $this->getResponse($result);
    }

    public function crosscode()
    {
        $result = $this->load->controller('fe/api/market/crosscode');
        $this->getResponse($result);
    }

    public function responsibles()
    {
        $result = $this->load->controller('fe/api/market/responsibles');
        $this->getResponse($result);
    }

    public function deliveryPrice()
    {
        $result = $this->load->controller('fe/api/market/delivery_price');
        $this->getResponse($result);
    }

    // Deprecated
    // public function discount()
    // {
    //     $result = $this->load->controller('fe/api/market/discount');
    //     $this->getResponse($result);
    // }

    public function documentSend()
    {
        $result = $this->load->controller('fe/api/market/document/send');
        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($result));
    }

    public function documentDownload()
    {
        $order_id = $this->request->get['order_id'] ?? 0;
        $result = $this->load->controller('fe/api/market/document/download');

        $this->response->addHeader("Content-Disposition: attachment; filename=\"order_{$order_id}.json\"");
        $this->response->addHeader("Content-Type: application/json");

        $this->response->setOutput(json_encode($result));
    }

    private function getResponse($result)
    {
        if (!$result) {
            $this->json['code'] = 201;
            $this->json['message'] = 'Ошибка.';
        }
        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($this->json));
    }

    public function productDay() {
        $curl = curl_init();

        $url = 'http://87.255.197.177:5581/torgupr/hs/market/productDay';
        $username = 'admin';
            $password = '4217777';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $response = curl_exec($ch);
        curl_close($ch);

        $json = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);

        $data = json_decode($json,true);

        if (!$data['message']) {
            $data = json_decode('{"code": 200, "message": [{"id": "80fd1572-d247-11e1-8039-000423d696ae", "percent": 0 }]}',true);
        }

        $this->load->model('fe/catalog/product');
        $product = $this->model_fe_catalog_product->getProductByGuid($data['message'][0]['id']);

        $this->load->model('setting/module');

        $post = array(
            'name' => 'ТОВАР ДНЯ!',
            'product_name' => '',
            'product' => [
                0 => $product['product_id']
            ],
            'limit' => 1,
            'width' => $data['message'][0]['percent'],
            'height' => 200,
            'status' => 1,
        );

        $this->model_setting_module->editModule(28, $post);
    }
}
