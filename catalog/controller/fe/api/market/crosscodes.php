<?php
class ControllerFeApiMarketCrosscodes extends Controller {
	public function index() {
        if (!isset($this->request->get['oem'])) {
            $this->error('OEM');
            return;
        }
    
        $json = $this->json([
            'oem' => $this->request->get['oem']
        ]);

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function json($args) {
        if (!isset($args['oem'])) {
            $this->error('OEM');
            return;
        }

        $json = $this->load->controller('fe/api/laximo/find_oem/json', ['oem' => $args['oem']]);

        $crosscodes = array();

        foreach ($json['data']->detail as $detail) {
            $crosscodes[strval($detail->attributes()->oem)] = 1;
            if (is_iterable($detail->replacements->replacement)) {
                foreach ($detail->replacements->replacement as $replacement) {
                    if ($replacement->detail) {
                        $crosscodes[strval($replacement->detail->attributes()->oem)] = 1;
                    }
                }
            } else {
                if ($detail->replacements->replacement->detail) {
                    $crosscodes[strval($detail->replacements->replacement->detail->attributes()->oem)] = 1;
                }
            }
        }

        $json = [];
        foreach ($crosscodes as $key => $value) {
            $json['data'][] = $key;
        }

        return $json;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
