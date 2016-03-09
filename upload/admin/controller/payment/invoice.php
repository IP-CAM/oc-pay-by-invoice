<?php
class ControllerPaymentInvoice extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/invoice');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('invoice', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_customer_groups'] = $this->language->get('entry_customer_groups');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/invoice', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/invoice', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('localisation/language');

		$data['languages'] = $languages;

		if (isset($this->request->post['invoice_order_status_id'])) {
			$data['invoice_order_status_id'] = $this->request->post['invoice_order_status_id'];
		} else {
			$data['invoice_order_status_id'] = $this->config->get('invoice_order_status_id');
		}

		$this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        if(isset($this->request->post['customer_groups']))
            $invoice_customer_groups = $this->request->post['customer_groups'];
        else
            $invoice_customer_groups = $this->config->get('invoice_customer_groups');
        if(!is_array($invoice_customer_groups))
            $invoice_customer_groups = array();
        $data['invoice_customer_groups'] = $invoice_customer_groups;

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['invoice_geo_zone_id'])) {
			$data['invoice_geo_zone_id'] = $this->request->post['invoice_geo_zone_id'];
		} else {
			$data['invoice_geo_zone_id'] = $this->config->get('invoice_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['invoice_status'])) {
			$data['invoice_status'] = $this->request->post['invoice_status'];
		} else {
			$data['invoice_status'] = $this->config->get('invoice_status');
		}

		if (isset($this->request->post['invoice_sort_order'])) {
			$data['invoice_sort_order'] = $this->request->post['invoice_sort_order'];
		} else {
			$data['invoice_sort_order'] = $this->config->get('invoice_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/invoice.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		return !$this->error;
	}
}
