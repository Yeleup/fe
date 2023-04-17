<?php
class ControllerFeCommonHome extends Controller {
	public function index() {
		$this->config->load('fe_config');

        $this->document->setTitle("5 элемент: интернет-магазин автозапчастей в Казахстане, Алматы");
        $this->document->setDescription("Пятый элемент: интернет-магазин автозапчастей в Казахстане, Алматы. Большой каталог деталей, автомасел и другой продукции по демократичным ценам.");

		$data['config_map_api_key'] = $this->config->get('fe_map_api_key');

		$this->load->model('fe/util/util');
		$util_banner_name = 'home_banner';
		$data['banner'] = $this->model_fe_util_util->getByName($util_banner_name);

		$link_list_details = $this->url->link('fe/pages/parts_catalogs', '', true) . '#/catalogs?catalogId=';
		$data['catalog_icons'] = [
			['href' => $link_list_details . 'chevrolet', 'img' => 'image/catalog/fe/static/home/top_cars/car1.png'],
			['href' => $link_list_details . 'GM_D201809', 'img' => 'image/catalog/fe/static/home/top_cars/car2.png'],
			['href' => $link_list_details . 'nissan', 'img' => 'image/catalog/fe/static/home/top_cars/car3.png'],
			['href' => $link_list_details . 'honda', 'img' => 'image/catalog/fe/static/home/top_cars/car4.png'],
			['href' => $link_list_details . 'hyundai', 'img' => 'image/catalog/fe/static/home/top_cars/car5.png'],
			['href' => $link_list_details . 'kia', 'img' => 'image/catalog/fe/static/home/top_cars/car6.png'],
			['href' => $link_list_details . 'subaru', 'img' => 'image/catalog/fe/static/home/top_cars/car7.png'],

			['href' => $link_list_details . 'mitsubishi', 'img' => 'image/catalog/fe/static/home/top_cars/car8.png'],
			['href' => $link_list_details . 'mazda', 'img' => 'image/catalog/fe/static/home/top_cars/car9.png'],
			['href' => $link_list_details . 'vw', 'img' => 'image/catalog/fe/static/home/top_cars/car10.png'],
			['href' => $link_list_details . 'toyota', 'img' => 'image/catalog/fe/static/home/top_cars/car11.png'],
			['href' => $link_list_details . 'suzuki', 'img' => 'image/catalog/fe/static/home/top_cars/car12.png'],
			['href' => $link_list_details . 'ssangyong', 'img' => 'image/catalog/fe/static/home/top_cars/car13.png'],
		];
		$data['delivery_price'] = $this->model_fe_util_util->getByName('delivery_price')['int_val'] ?? '1000';

		$this->load->model('fe/market/subcategory');
		$data['prod_subcat'] = $this->model_fe_market_subcategory->getAll();
        foreach ($data['prod_subcat'] as $key => $subcat) {
            $data['prod_subcat'][$key]['link'] = $this->url->link('katalog?subcat=' . $subcat['id'], '', true);
        }			
		$data['customer_id'] = $this->session->data['customer_id'] ?? 0;
		$data['logged'] = $this->customer->isLogged();

        $data['route'] = $this->request->get['route'] ?? 'fe/common/home';

        $data['search_text'] = (isset($this->request->get['search']) ? $this->request->get['search'] : '');
		$data['search_bar'] = $this->load->view('fe/includes/common/search_bar', $data);
		$data['search'] = $this->load->controller('fe/includes/common/search');
		//$data['search_laximo'] = $this->load->controller('fe/includes/common/search_laximo');
		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

        $this->load->model('setting/module');
        $product_day_module_id = 28;
        $setting_info = $this->model_setting_module->getModule($product_day_module_id);

        $data['product_day'] = $this->load->controller('extension/module/product_day', $setting_info);

		$this->response->setOutput($this->load->view('fe/common/home', $data));
	}
}
