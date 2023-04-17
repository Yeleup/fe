<?php
class ControllerFePagesGoods extends Controller {
	public function index() {
		$this->config->load('fe_config');

		$this->load->model('catalog/product');

		if (isset($this->request->get['search'])) {
			$search = $this->request->get['search'];
		} else {
			$search = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		// $limit = 12;

		// $url = '';

		// if (isset($this->request->get['search'])) {
		// 	$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		// }

		// $filter = [
		// 	'filter_name'         => $search,
		// 	'filter_tag'          => $search,
		// 	'filter_description'  => 'true',
		// 	'filter_category_id'  => 0,
		// 	'filter_sub_category' => 'true',
		// 	'sort'                => 'p.sort_order',
		// 	'order'               => 'ASC',
		// 	'start'               => ($page - 1) * $limit,
		// 	'limit'               => $limit
		// ];

		// $product_total = $this->model_catalog_product->getTotalProducts($filter);

		// $result = $this->model_catalog_product->getProducts($filter);

		// $data['products'] = $result;

		if (!isset($this->request->get['catalog']) ||
			!isset($this->request->get['vehicle_id']) ||
			!isset($this->request->get['ssd'])) {
			$data['products'] = [];
		}
		// else {
		// 	$units = $this->load->controller('fe/api/laximo/list_units/json', [
		// 		'catalog' => $this->request->get['catalog'],
		// 		'vehicle_id' => $this->request->get['vehicle_id'],
		// 		'ssd' => $this->request->get['ssd']
		// 	]);
		// 	$data['products'] = $units->row;
		// }

		// $pagination = new Pagination();
		// $pagination->total = $product_total;
		// $pagination->page = $page;
		// $pagination->limit = $limit;
		// $pagination->url = $this->url->link('fe/pages/goods', $url . '&page={page}');

		// $data['pagination'] = $pagination->render();

		$this->document->setTitle("Товары");

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/goods', $data));
	}
}
