<?php
class ControllerFeApiLaximoListCatalogs extends Controller {
	public function index() {
        $result = $this->json();
        $json = $result->ListCatalogs ?? $result;

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function json() {
		if (!$this->load->controller('fe/api/laximo/validate/customer')) return [];

        $this->load->library('LaximoConnector');

        $soap = new LaximoConnectorOem();
        $result = $soap->query('ListCatalogs:Locale=ru_RU|ssd=');

        $link_get_wizard = $this->url->link('fe/api/laximo/get_wizard', '', true) . '&catalog=';

        foreach ($result->ListCatalogs->row as $catalog) {
            $catalog->addChild('_links');
            $link = htmlspecialchars($link_get_wizard . $catalog->attributes()->code);
            $catalog->_links->addChild('GetWizard2', $link);
        }

        return $result;
    }
}
