<?php
class ControllerFeApiMarketCategory extends Controller {
    public function index() {
        $this->load->library('MarketGuzzleFacade');
        $client = new MarketGuzzleFacade();
        $result = $client->get('/market/categories/get/list');

        $this->load->model('fe/catalog/category');
        $this->load->model('fe/localization/language');

        $language_id = $this->model_fe_localization_language->getCurrentId();

        foreach ($result->message as $category) {
            $c = $this->model_fe_catalog_category->getCategoryByGuid($category->guid);
            if (!$c) {
                $this->model_fe_catalog_category->addCategory([
                    'parent_id' => 0,
                    'column' => 0,
                    'sort_order' => 0,
                    'status' => 1,
                    'guid' => $category->guid,
                    'category_description' => [
                        $language_id => [
                            'name' => $category->name,
                            'description' => '',
                            'meta_title' => $category->name,
                            'meta_description' => '',
                            'meta_keyword' => ''
                        ],
                    ],
                ]);
            }
        }
        return true;
    }
}
