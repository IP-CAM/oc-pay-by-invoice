<?php
class ModelPaymentInvoice extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/invoice');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('invoice_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        $groups = $this->config->get('invoice_customer_groups');
        if (!is_array($groups))
            return array();

        if (!in_array($this->customer->getGroupId(), $groups))
            return array();

		if (!$this->config->get('invoice_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'invoice',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('invoice_sort_order')
			);
		}

		return $method_data;
	}
}
