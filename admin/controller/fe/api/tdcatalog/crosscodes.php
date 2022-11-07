<?php
class ControllerFeApiTdcatalogCrosscodes extends Controller {

    public function index()
    {
        $this->load->model('fe/tdcatalog/crosscodes');
        $this->model_fe_tdcatalog_crosscodes->sync();
        return true;
    }

}
