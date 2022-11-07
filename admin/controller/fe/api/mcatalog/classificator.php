<?php
class ControllerFeApiMcatalogClassificator extends Controller {

    public function index() {
        $this->load->model('fe/mcatalog/http_client');
        $this->load->model('fe/mcatalog/classificator');
        $result = $this->model_fe_mcatalog_http_client->request('/classificator');

        foreach ($result['items'] as $item) {
            $classificator = $this->model_fe_mcatalog_classificator->getByGuid($item['id']);
            if (!$classificator) {
                $this->model_fe_mcatalog_classificator->add([
                    'guid' => $item['id'],
                    'name' => $item['value']
                ]);
            }
        }

        return $result;
    }

}
