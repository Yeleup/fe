<?php
class ModelFeTdcatalogCrosscodes extends Model {

    public function sync()
    {
        $this->load->library('TdcatalogGuzzleFacade');
        $client = new TdcatalogGuzzleFacade();

        $this->load->model('fe/market/crosscode');
        $this->load->model('catalog/product');
        $this->load->model('fe/util/crosscode');

        $products = $this->model_catalog_product->getProducts();

        foreach ($products as $product) {
            echo "<pre>";
            $crosscodes = $this->model_fe_market_crosscode->getByProductId($product['product_id']);

            foreach ($crosscodes as $crosscode) {
                $td_crosses = json_decode($client->get('/cross-ref/' . $crosscode['crosscode']));

                // var_dump($td_crosses);
                foreach ($td_crosses as $td_cross) {
                    $cross_norm = $this->model_fe_util_crosscode->normalize($td_cross->Snumber);
                    $cross = $this->model_fe_market_crosscode->getByCrosscodeId($cross_norm);
                    if ($cross) {
                        var_dump($cross_norm);
                        var_dump($cross);
                        die();
                    }
                }

            }

            echo "</pre>";
        }
    }

}
