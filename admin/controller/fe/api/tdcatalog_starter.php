<?php
class ControllerFeApiTdcatalogStarter extends Controller {
    protected $json = [
        'code' => 200,
        'message' => 'Синхронизировано.',
    ];

    public function crosscodes()
    {
        $result = $this->load->controller('fe/api/tdcatalog/crosscodes');
        $this->getResponse($result);
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
}
