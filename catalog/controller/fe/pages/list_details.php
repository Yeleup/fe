<?php
class ControllerFePagesListDetails extends Controller {
	public function index() {
		$this->load->model('fe/catalog/product');

		$products = [];

		$this->document->setTitle("Каталог запчастей для японских и корейский авто | Интернет-магазин Пятый элемент ");
		$this->document->setDescription("Продажа запасных частей для японских и корейских автомобилей. Интернет-магазин Пятый элемент предлагает широкий ассортимент и привлекательные цены.");

		$limit = 12;
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}
		$offset = ($page - 1) * $limit;
		$products_count = 0;
		$params = '';

		if (isset($this->request->get['catalog']) && isset($this->request->get['unit_id']) && isset($this->request->get['ssd'])) {
			$laximo_products = $this->load->controller('fe/api/laximo/list_detail_by_unit/json', [
				'catalog' => $this->request->get['catalog'],
				'unit_id' => $this->request->get['unit_id'],
				'ssd' => $this->request->get['ssd']
			]);

			$products = [];
			foreach ($laximo_products->row as $laximo_product) {
				$crosscode = strval($laximo_product['oem']);
				if (!empty($crosscode)) {
					$products_result = $this->model_fe_catalog_product->getByCrosscode($crosscode);

					if ($products_result) {
						array_push($products, ...$products_result);
					}
				}
			}
		} elseif (isset($this->request->get['search'])) {
			$params = '&search=' . $this->request->get['search'];
			$products = $this->model_fe_catalog_product->getProductsByOem($this->request->get['search']);
			if (!sizeof($products)) {
				$products = $this->model_fe_catalog_product->getByNameSearch($this->request->get['search'], false, $offset, $limit);
				$products_count = (int)$this->model_fe_catalog_product->getByNameSearch($this->request->get['search'], true)['count'];
			}
		} elseif (isset($this->request->get['subcat'])) {
			$products = $this->model_fe_catalog_product->getBySubcategoryId($this->request->get['subcat'], false, $offset, $limit);
			$products_count = $this->model_fe_catalog_product->getBySubcategoryId($this->request->get['subcat'], true)['count'];
			$params = "&subcat={$this->request->get['subcat']}";
		} else {
			$products = $this->model_fe_catalog_product->get();
		}

		$pagination = new Pagination();
		$pagination->total = $products_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
        if (isset($this->request->get['subcat'])) {
            $pagination->url = $this->url->link('katalog?subcat=' . $_GET['subcat'], '&page={page}', true);
        } else {
            $pagination->url = $this->url->link('fe/pages/list_details', $params . '&page={page}', true);
        }
		$data['pagination'] = $pagination->render();

		$data['products'] = [];

		foreach ($products as $product) {
            if (!isset($product['product_id'])) {
                print_r($products);die;
            }
			$product = $this->model_fe_catalog_product->getFullById($product['product_id']);
			if ($product) {
				$data['products'][] = $product;
			}
		}

		$this->load->model('fe/market/subcategory');
		$data['prod_subcat'] = $this->model_fe_market_subcategory->getAll();
        foreach ($data['prod_subcat'] as $key => $subcat) {
            $data['prod_subcat'][$key]['link'] = $this->url->link('katalog?subcat=' . $subcat['id'], '', true);
        }

		$category = [];
		if (isset($this->request->get['subcat'])) {
		    $category = $this->model_fe_market_subcategory->getOneById($this->request->get['subcat']);
		}
		if (!empty($category['name'])) {
		    if ($category['id'] == 1) {
		        $data['category_name'] = 'Моторное масло';
                $this->document->setTitle("Купить моторные масла по выгодной цене | Магазин автомасел");
                $this->document->setDescription("Купить моторные масла в Алматы по выгодной цене. Интернет-магазин автомасел Пятый элемент предлагает широкий ассортимент запчастей для различных авто.");
            } elseif ($category['id'] == 2) {
		        $data['category_name'] = 'Ветровики';
                $this->document->setTitle("Купить ветровики (дефлекторы окон) на автомобиль по выгодной цене");
                $this->document->setDescription("Интернет магазин Пятый элемент предлагает купить ветровики (дефлекторы окон) на автомобиль. Выгодные цены и широкий выбор автозапчастей. Консультирование по телефону +7 (727) 390 91 91");
            } elseif ($category['id'] == 3) {
		        $data['category_name'] = 'Дворники';
                $this->document->setTitle("Купить дворники (щетки стеклоочистителя) на автомобиль по выгодным ценам");
                $this->document->setDescription("Интернет магазин Пятый элемент предлагает купить дворники (щетки стеклоочистителя) на автомобиль. Выгодные цены и широкий выбор автозапчастей. Консультирование по телефону +7 (727) 390 91 91");
            } elseif ($category['id'] == 4) {
		        $data['category_name'] = 'Автолампы';
                $this->document->setTitle("Купить автолампы | Автомобильные лампы по выгодной цене");
                $this->document->setDescription("Интернет магазин Пятый элемент предлагает купить лампы на автомобиль. Выгодные цены и широкий выбор автоламп. Консультирование по телефону +7 (727) 390 91 91");
		    } elseif ($category['id'] == 5) {
		        $data['category_name'] = 'Мухобойки';
                $this->document->setTitle("Купить мухобойки (дефлекторы капота) на автомобиль по выгодным ценам");
                $this->document->setDescription("Интернет магазин Пятый элемент предлагает купить мухобойки (дефлекторы капота) на автомобиль. Выгодные цены и широкий выбор автозапчастей. Консультирование по телефону +7 (727) 390 91 91");
		    } else {
		        $data['category_name'] = $category['name'];
		    }
            if (isset($this->request->get['page'])) {
                $this->document->setTitle($data['category_name'] . ", страница " . $this->request->get['page'] . " | Пятый элемент");
                $this->document->setDescription($data['category_name'] . ". Продажа запасных частей для японских и корейских автомобилей. Интернет-магазин Пятый элемент предлагает широкий ассортимент и привлекательные цены. Страница " . $this->request->get['page'] . ".");
            }
		} else {
		    $data['category_name'] = 'Каталог';
		}

		//************************************************************************************************** */
		$data['logged'] = $this->customer->isLogged();
		$data['catalogs'] = $_SERVER['REQUEST_URI'] == '/katalog&search=!';

		$this->load->model('fe/market/client_category');

		$customer_id = $this->session->data['customer_id'] ?? 0;
        $client_category = $this->model_fe_market_client_category->getByCustomerId($customer_id);
		if (!$client_category) {
            $client_category = $this->model_fe_market_client_category->getWhereRetail();
        }
        $client_category_id = $client_category ? $client_category['id'] : '';

        $data['client_category_id'] = $client_category_id;
		//************************************************************************************************** */

        $data['search_text'] = '';
        if (isset($this->request->get['search'])) {
            $data['search_text'] = $this->request->get['search'];
        }

		$data['product_search'] = $this->load->view('fe/includes/common/search_bar', $data);
		$data['link_product_detail'] = $this->url->link('fe/pages/details', '', true);

		$data['footer'] = $this->load->controller('fe/common/footer');
		$data['header'] = $this->load->controller('fe/common/header');

		$this->response->setOutput($this->load->view('fe/pages/list_details', $data));
	}
}
