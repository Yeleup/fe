<?php
class ControllerFeApiLaximoFindReplacements extends Controller {
	public function index() {
        if (!isset($this->request->get['detail_id'])) {
            $this->error('Detail ID');
            return;
        }

        $json = $this->json([
            'detail_id' => $this->request->get['detail_id'],
        ]);

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function json($args) {
		if (!$this->load->controller('fe/api/laximo/validate/customer')) return [];

        if (!isset($args['detail_id'])) {
            $this->error('Detail ID');
            return;
        }

        $this->load->library('LaximoConnector');

        $soap = new LaximoConnectorAm();
        $query = 'FindReplacements:Locale=%s|DetailId=%s';
        $query = sprintf(
            $query,
            'ru_RU',
            $args['detail_id']
        );
        $result = $soap->query($query);

        $json = $result->FindReplacements;

        // $link_find_oem = $this->url->link('fe/api/laximo/find_oem', '', true) . "&oem=%s";
        // foreach ($json->row as $detail) {
        //     $detail->addChild('_links');
        //     $link = sprintf(
        //         $link_find_oem,
        //         preg_replace('/\s/', '', $detail->attributes()->oem)
        //     );
        //     $detail->_links->addChild('link', htmlspecialchars($link));
        // }

		return $json;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
