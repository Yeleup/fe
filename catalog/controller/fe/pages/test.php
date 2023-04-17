<?php
class ControllerFePagesTest extends Controller {
	public function index() {

		if (isset($this->request->get['article']) && isset($this->request->get['brand'])) {
			var_dump($this->request->get);
			$output = "";
		} else {
			$output = '
			<div id="parts-catalog"
			data-key="TWS-D117AD4A-6611-4CD7-9742-CFC8C1EEF593"
			data-back-url="/index.php?route=fe/pages/test?article={article}&brand={brand}"
			data-language="ru"
			></div>

			<script type="text/javascript" src="https://gui.parts-catalogs.com/v2/parts-catalogs.js"></script>
			';
		}
	    $this->response->setOutput($output);
	}
}
