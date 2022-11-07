<?php
class ControllerFeApiCommonHomeSearch extends Controller {
    public function index() {
        if (!isset($this->request->get['search'])) {
            $this->error('Ident String');
            return;
        }

        $result = $this->json([
            'search' => $this->request->get['search']
        ]);

        $json['data'] = $result;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function json($args) {
        $search = $args['search'] ?? null;
        if (!$search) {
            $this->error('Ident String');
            return;
        }

        $result = $this->load->controller('fe/api/laximo/find_vehicle/getRedirect', [
            'ident_string' => $search
        ]);

        if (!$result) {
            $result = [
                'redirect' => $this->url->link('fe/pages/list_details', '', true) . '&search=' . $search
            ];
        }

        return $result;
    }

    private function error($err) {
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['error' => $err . ' is not set.']));
    }
}
