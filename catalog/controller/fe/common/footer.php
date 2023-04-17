<?php
class ControllerFeCommonFooter extends Controller {
	public function index() {
		$data['link_home'] = $this->url->link('fe/common/home', '', true);
		$data['link_about'] = $this->url->link('fe/common/about', '', true);
		$data['link_cooperation'] = $this->url->link('fe/common/cooperation', '', true);
		$data['link_list_details'] = $this->url->link('fe/pages/list_details', '', true);
		$data['link_modal_includes'] = $this->url->link('fe/includes', '', true);

		$data['modal_public_offer'] = $this->load->controller('fe/modals/public_offer');
		$data['modal_privacy_policy'] = $this->load->controller('fe/modals/privacy_policy');
		$data['modal_payment_refund'] = $this->load->controller('fe/modals/payment_refund');

		return $this->load->view('fe/common/footer', $data);
	}
}
