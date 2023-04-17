<?php
class ControllerFePagesListUnits extends Controller {
	public function index() {
		$vehicle_id = $this->request->get['vehicle_id'] ?? null;
		$catalog = $this->request->get['catalog'] ?? null;
		$ssd = $this->request->get['ssd'] ?? null;
		$data['vehicle_id'] = $vehicle_id;
		$data['parent_id'] = $this->request->get['parent_id'] ?? null;

		if ($vehicle_id && $catalog && $ssd) {
			$results = $this->load->controller('fe/api/laximo/list_units/json', [
				'catalog' => $catalog,
				'vehicle_id' => $vehicle_id,
				'ssd' => $ssd
			]);

			$data['products'] = [];
			foreach ($results->row as $result) {
				$item = [];
				$item['image_url'] = str_replace('%size%', '250', strval($result['imageurl']));
				$item['description'] = $result['name'];
				$item['link_details'] = $result->_links->link_list_details ?? null;
				$data['products'][] = $item;
			}
		} else {
			$data['products'] = [];
		}

		$this->document->setTitle("Узлы");

		$data['product_search'] = $this->load->view('fe/includes/pages/product_search');

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/list_units', $data));
	}
}
