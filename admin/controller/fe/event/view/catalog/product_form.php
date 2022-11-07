<?php
class ControllerFeEventViewCatalogProductForm extends Controller {

    public function before(&$route, &$args) {

    }

	public function after(&$route, &$args, &$output) {
        $this->load->model('catalog/product');
        if (isset($this->request->get['product_id'])) {
            $product = $this->model_catalog_product->getProduct($this->request->get['product_id']);

            // Page Header Button Synchronize
            $search_str = '/<div id="content">\s*<div class="page-header">\s*<div class="container-fluid">\s*<div class="pull-right">/';
            $insert_str = '
            <button id="pageHeaderBtnSynchronize" type="button" data-toggle="tooltip" class="btn btn-primary" data-original-title="Синхронизировать">
                <i class="fa fa-refresh"></i>
            </button>
            <script>
                $(document).ready(() => {
                    $("#pageHeaderBtnSynchronize").on("click", () => {
                        $.ajax({
                            url: "index.php?route=fe/api/market_starter/productOne&user_token=%s&product_id=%s",
                            success: (res) => {
                                console.log(res);
                            }
                        });
                    });
                });
            </script>
            ';
            $insert_str = sprintf($insert_str, $this->session->data['user_token'], $product['product_id']);
            preg_match($search_str, $output, $matches, PREG_OFFSET_CAPTURE);
            if ($matches) {
                $pos = strlen($matches[0][0]) + $matches[0][1];
                $output = substr($output, 0, $pos) . $insert_str . substr($output, $pos);
            }

            // Nav Tabs
            $search_str = '/<ul class="nav nav-tabs">[\s\S]*?<\/ul>/';
            $insert_str = '
            <li>
                <a href="#tab-prices" data-toggle="tab">
                    Цены
                </a>
            </li>
            ';
            preg_match($search_str, $output, $matches, PREG_OFFSET_CAPTURE);
            if ($matches) {
                $pos = strlen($matches[0][0]) + $matches[0][1] - 5;
                $output = substr($output, 0, $pos) . $insert_str . substr($output, $pos);
            }

            // Nav Tab Data
            $this->load->model('fe/market/price');
            $prices = $this->model_fe_market_price->getPricesByProductId($this->request->get['product_id']);
            $tab_body = '';
            foreach ($prices as $price) {
                $format = '<div><b>%s: %s тг</b> (%s)</div>';
                $tab_body .= sprintf($format, $price['name'], (int)$price['price'], $price['guid']);
            }
            $search_str = '/<div class="tab-content">/';
            $insert_str = '
            <div id="tab-prices" class="tab-pane">
                %s
            </div>
            ';
            $insert_str = sprintf($insert_str, $tab_body);
            preg_match($search_str, $output, $matches, PREG_OFFSET_CAPTURE);
            if ($matches) {
                $pos = $matches[0][1] + strlen($matches[0][0]);
                $output = substr($output, 0, $pos) . $insert_str . substr($output, $pos);
            }

            // GUID field
            $search_str = '<div class="tab-pane" id="tab-data">';
            $pos = strpos($output, $search_str);
            $pos += strlen($search_str);
            $insert_str = '
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-guid">GUID</label>
                    <div class="col-sm-10">
                        <input type="text" name="guid" value="%s" placeholder="GUID" id="input-guid" class="form-control" disabled/>
                    </div>
                </div>
            ';
            $insert_str = sprintf($insert_str, $product['guid']);
            $output = substr($output, 0, $pos) . $insert_str . substr($output, $pos);
        }
    }

}
