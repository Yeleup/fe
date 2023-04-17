<?php
// Deprecated
class ControllerFeApiMarketDiscount extends Controller {
    public function index() {
        // $this->load->library('MarketGuzzleFacade');
        // $client = new MarketGuzzleFacade();
        // $result = $client->get('/market/discounts/get');
        //
        // $this->load->model('fe/catalog/category');
        // $this->load->model('fe/market/price_for');
        // $this->load->model('fe/market/product_fe_discount');
        // $this->load->model('fe/market/product_fe_discount_to_price');
        // $this->load->model('fe/market/product_fe_discount_price_to_category');
        //
        // foreach ($result->message as $discount) {
        //     $discount_id = $this->model_fe_market_product_fe_discount->add([
        //         'guid' => $discount->guid,
        //         'name' => $discount->name,
        //     ]);
        //
        //     foreach ($discount->categories as $discount_to_price) {
        //         $price_id = $this->model_fe_market_price_for->getIdByGuid($discount_to_price->price);
        //
        //         if ($price_id) {
        //             $discount_price_id = $this->model_fe_market_product_fe_discount_to_price->add([
        //                 'discount_id' => $discount_id,
        //                 'price_id' => $price_id,
        //             ]);
        //
        //             foreach ($discount_to_price->values as $discount_price_to_category) {
        //                 $category_id = $this->model_fe_catalog_category->getIdByGuid($discount_price_to_category->category);
        //
        //                 if ($category_id) {
        //                     $discount_price_to_category_id = $this->model_fe_market_product_fe_discount_price_to_category->add([
        //                         'discount_price_id' => $discount_price_id,
        //                         'category_id' => $category_id,
        //                         'percent' => $discount_price_to_category->percent,
        //                     ]);
        //                 }
        //             }
        //         }
        //     }
        // }
        //
        // return true;
        return false;
    }
}
