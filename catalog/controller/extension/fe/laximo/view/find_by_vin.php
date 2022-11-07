<?php

class ControllerExtensionFeLaximoViewFindByVin extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->library('laximo');
    }

    public function getRedirectLinkArgs($args) {
        $vin = $args['vin'] ?? '';
        $catalog = $args['catalog'] ?? '';
        return $this->getRedirectLink($vin, $catalog);
    }

    public function getRedirectLink(string $vin, string $catalog): ?string {
        $result = $this->laximo->findVehicleByVin($catalog, $vin);
        $link = $result['row']['0']['_links']['ListQuickGroup'] ?? null;
        if ($link) {
            $link = base64_encode($link);
            $link = $this->url->link('extension/fe/laximo/view/quick_group', '', true) . "&url=$link";
        }
        return $link;
    }

    public function index() {
        $catalog = $this->request->get['catalog'] ?? '';
        $vin = $this->request->get['vin'] ?? '';
        $link = $this->getRedirectLink($vin, $catalog);
        if ($link) {
            $this->response->redirect($link);
        } else {
            $this->response->redirect($this->url->link('common/home', '', true));
        }
    }

}
