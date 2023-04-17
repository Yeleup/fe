<?php
class ControllerFeApiLaximoStarter extends Controller {
    protected $json = [
        'code' => 200,
        'message' => 'Синхронизировано.',
    ];

    public function index() {
        $this->response->setOutput("Starter");
    }

    public function findPartReferences() {
        $oem = $this->request->get['oem'];
        $result = $this->load->controller('fe/api/laximo/find_part_references/json', [
            'oem' => $oem
        ]);
        $this->json['message'] = $result;
        $this->getResponse($result);
    }

    public function findApplicableVehicles() {
        $oem = $this->request->get['oem'];
        $catalog = $this->request->get['catalog'];
        $result = $this->load->controller('fe/api/laximo/find_applicable_vehicles/json', [
            'catalog' => $catalog,
            'oem' => $oem
        ]);
        $this->json['message'] = $result;
        $this->getResponse($result);
    }

    public function getOemPartApplicability() {
        $oem = $this->request->get['oem'];
        $catalog = $this->request->get['catalog'];
        $ssd = $this->request->get['ssd'];
        $result = $this->load->controller('fe/api/laximo/get_oem_part_applicability/json', [
            'catalog' => $catalog,
            'oem' => $oem,
            'ssd' => $ssd
        ]);
        $this->json['message'] = $result;
        $this->getResponse($result);
    }

    public function synchronize() {
        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = 1;
        }

        $result = $this->load->controller('fe/api/laximo/synchronize/json', $limit);
        $this->json['message'] = $result;
        $this->getResponse($result);
    }

    public function cleanup() {
        $this->load->model('fe/laximo/cleanup');
        $result = $this->model_fe_laximo_cleanup->clean();
        $this->getResponse($result);
    }

    private function getResponse($result) {
        if (!$result) {
            $this->json['code'] = 201;
            $this->json['message'] = 'Ошибка.';
        }
        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($this->json));
    }
}
