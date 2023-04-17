<?php
class ControllerFeApiLaximoListDetailByUnit extends Controller {
	public function index() {
        if (!isset($this->request->get['catalog'])) {
            $this->error('Catalog');
            return;
        }
        if (!isset($this->request->get['unit_id'])) {
            $this->error('Unit ID');
            return;
        }
        if (!isset($this->request->get['ssd'])) {
            $this->error('SSD');
            return;
        }

        $json = $this->json([
            'catalog' => $this->request->get['catalog'],
            'unit_id' => $this->request->get['unit_id'],
            'ssd' => $this->request->get['ssd']
        ]);

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function json($args) {
		if (!$this->load->controller('fe/api/laximo/validate/customer')) return [];

        if (!isset($args['catalog'])) {
            $this->error('Catalog');
            return;
        }
        if (!isset($args['unit_id'])) {
            $this->error('Unit ID');
            return;
        }
        if (!isset($args['ssd'])) {
            $this->error('SSD');
            return;
        }

        $this->load->library('LaximoConnector');

		$soap = new LaximoConnectorOem();
        $query = 'ListDetailByUnit:Locale=%s|Catalog=%s|UnitId=%s|ssd=%s|Localized=true';
        $query = sprintf(
            $query,
            'ru_RU',
            $args['catalog'],
            $args['unit_id'],
            $args['ssd']
        );
        $result = $soap->query($query);

		$json = $result->ListDetailsByUnit;

        $link_find_oem = $this->url->link('fe/api/laximo/find_oem', '', true) . "&oem=%s";
        $link_product_by_oem = $this->url->link('fe/api/catalog/product', '', true) . "&oem=%s";
        $link_crosscodes = $this->url->link('fe/api/market/crosscodes', '', true) . "&oem=%s";
        foreach ($json->row as $detail) {
            $oem = preg_replace('/\s/', '', $detail->attributes()->oem);

            if (!empty($oem)) {
                // Find OEM Link
                $detail->addChild('_links');
                $link = sprintf(
                    $link_find_oem,
                    $oem
                );
                $detail->_links->addChild('link', htmlspecialchars($link));

                // Product by OEM Link
                $link = sprintf(
                    $link_product_by_oem,
                    $oem
                );
                $detail->_links->addChild('link_product', htmlspecialchars($link));

                // Crosscodes Link
                $link = sprintf(
                    $link_crosscodes,
                    $oem
                );
                $detail->_links->addChild('link_crosscodes', htmlspecialchars($link));
            }
        }

		return $json;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
