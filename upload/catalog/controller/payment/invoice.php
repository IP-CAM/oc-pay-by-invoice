<?php
class ControllerPaymentInvoice extends Controller {
	public function index() {
		$this->load->language('payment/invoice');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/invoice.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/invoice.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/invoice.tpl', $data);
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'invoice') {
			$this->load->language('payment/invoice');

			$this->load->model('checkout/order');

			$comment = $this->language->get('text_title');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('invoice_order_status_id'), $comment, true);
		}
	}
}
