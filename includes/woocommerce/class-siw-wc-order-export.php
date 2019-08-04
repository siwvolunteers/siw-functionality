<?php

/**
 * Exporteert aanmelding naar Plato
 *
 * @package   SIW\WooCommerce
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Plato_Export_Application
 */

class SIW_WC_Order_Export {

	/**
	 * Aantal gefaalde geexporteerde aanmeldingen
	 *
	 * @var int
	 */
	protected $failed_count = 0;

	/**
	 * Aantal succesvol geexporteerde aanmeldingen
	 *
	 * @var int
	 */
	protected $success_count = 0;

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_order_actions', [ $self, 'add_order_action'] );
		add_action( 'woocommerce_order_action_siw_export_to_plato', [ $self, 'export_order'] );
		add_action( 'woocommerce_order_status_processing', [ $self, 'export_order'] );
	}

	/**
	 * Voeg orderactie voor export naar Plato toe
	 *
	 * @param array $actions
	 * @return array
	 */
	public function add_order_action( array $actions ) {
		global $theorder;
		if ( $theorder->is_paid() ) {
			$actions['siw_export_to_plato'] = __( 'Exporteer naar PLATO', 'siw' );
		}
		return $actions;
	}

	/**
	 * Exporteert aanmelding naar plato
	 *
	 * @param int|WC_Order $order_id
	 */
	function export_order( $order ) {

		if ( ! is_object( $order ) ) {
			$order = new WC_Order( $order );
		}
	
		/* Haal velden voor aanmelding op */
		$order_data = $this->get_order_data( $order );
	
		/* Elk project per aanmelding apart exporteren. */
		foreach ( $order->get_items() as $item_id => $item_data ) {
			$product = $order->get_product_from_item( $item_data ); //TODO: check of product nog bestaat
			$result = $this->export_application( $order_data, $product );
			$order->add_order_note( $result['message'] );
		}
	
		/* Resultaat opslaan bij aanmelding */
		if ( 0 != $this->failed_count ) {
			$order->update_meta_data( '_exported_to_plato', 'failed' );
			$order->save();
		}
		elseif ( 0 != $this->success_count ) {
			$order->update_meta_data( '_exported_to_plato', 'success' );
			$order->save();
		}
	}
	
	/**
	 * Exporteert aanmelding voor 1 project naar Plato
	 *
	 * @param array $order_data
	 * @param array $product
	 * @return array
	 */
	protected function export_application( $order_data, $product ) {
		
		$projectcode = $product->get_sku();
		$order_data['choice1'] = $projectcode;
		$export = new SIW_Plato_Export_Application;
		$result = $export->run( $order_data );

		if ( true == $result['success'] ) {
			$this->success_count++;
		}
		else {
			$this->failed_count++;
		}
		return $result;
	}

	/**
	 * Genereert array met gegevens aanmelding voor export-xml
	 *
	 * @param WC_Order $order Aanmelding
	 * @return array
	 * 
	 * @todo country en occupation uit order halen/uitvragen
	 */
	protected function get_order_data( WC_Order $order ) {
		return [
			'firstname'         => $order->get_billing_first_name(),
			'lastname'          => $order->get_billing_last_name(),
			'sex'               => $order->get_meta( '_billing_gender' ),
			'birthdate'         => date( 'Y-m-d', strtotime( $order->get_meta( '_billing_dob' ) ) ),
			'email'             => $order->get_billing_country(),
			'nationality'       => $order->get_meta( '_billing_nationality' ),
			'telephone'         => $order->get_billing_phone(),
			'address1'          => sprintf( '%s %s', $order->get_billing_address_1(), $order->get_meta( '_billing_housenumber' ) ),
			'zip'               => $order->get_billing_postcode(),
			'city'              => $order->get_billing_city(),
			'country'           => 'NLD',
			'occupation'        => 'OTH',
			'emergency_contact' => sprintf( '%s %s', $order->get_meta( 'emergencyContactName' ), $order->get_meta( 'emergencyContactPhone' ) ),
			'language1'         => $order->get_meta( 'language1' ),
			'language2'         => $order->get_meta( 'language2' ),
			'language3'         => $order->get_meta( 'language3' ),
			'langlevel1'        => $order->get_meta( 'language1Skill' ),
			'langlevel2'        => $order->get_meta( 'language2Skill' ),
			'langlevel3'        => $order->get_meta( 'language3Skill' ),
			'special_needs'     => $order->get_meta( 'healthIssues' ),
			'experience'        => $order->get_meta( 'volunteerExperience' ),
			'motivation'        => $order->get_meta( 'motivation' ),
			'together_with'     => $order->get_meta( 'togetherWith' ),
			'req_sent_by'       => siw_get_option( 'plato_export_outgoing_placements_name' ),
			'req_sender_email'  => siw_get_option( 'plato_export_outgoing_placements_email' ),
			'date_filed'        => date( 'Y-m-d' ),
		];
	}
}