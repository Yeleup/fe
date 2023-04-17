<?php
class ControllerFeApiMarketProductImages extends Controller {

    public function index() {
        $this->load->model('fe/catalog/product');
        $this->load->model('fe/catalog/product_image');

        $image_path = 'catalog/fe/products/';
        $dir_path = DIR_IMAGE . $image_path;

        $files = array_diff( scandir($dir_path), ['.', '..'] );

        foreach ($files as $file) {
            $product_image_path = $image_path . $file;
            $file_parts = explode('.', $file);
            $file_name = $file_parts[0];
            $file_ext = $file_parts[1] ?? '';
            $file_name_parts = explode('_', $file_name);
            $guid = $file_name_parts[0];

            $product = $this->model_fe_catalog_product->getProductByGuid($guid);
            $product_id = $product['product_id'] ?? 0;

            if ($product_id) {
                $image_result = $this->model_fe_catalog_product_image->getByProductIdAndImage($product_id, $product_image_path);
                if (!$image_result) {
                    $this->model_fe_catalog_product_image->add([
                        'product_id' => $product_id,
                        'image' => $product_image_path,
                        'sort_order' => 1,
                    ]);
                }
            }
        }

        return true;
    }

}
