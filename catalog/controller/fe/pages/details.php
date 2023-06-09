<?php
class ControllerFePagesDetails extends Controller {
	public function index() {
		$this->load->model('fe/catalog/product');

		$this->config->load('fe_config');

		$this->document->setTitle("Детали");

		$product_id = $this->request->get['product_id'] ?? 0;
		$product = $this->model_fe_catalog_product->getFullById($product_id);

		$data['product'] = $product;

        $data['search_text'] = '';
        if (isset($this->request->get['search'])) {
            $data['search_text'] = $this->request->get['search'];
        }

        $data['product_search'] = $this->load->view('fe/includes/common/search_bar', $data);

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/details', $data));
	}
}
