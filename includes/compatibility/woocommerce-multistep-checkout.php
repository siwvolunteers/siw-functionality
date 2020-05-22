<?php

namespace SIW\Compatibility;

use SIW\Properties;

/**
 * Aanpassingen voor WooCommerce Multistep Checkout
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @see       https://woocommerce.com/
 * @since     3.1.?
 */
class WooCommerce_Multistep_Checkout {

	/**
	* Init
	*/
	public static function init() {
		$self = new self();

		$options = array_keys( $self->get_option_values() );

		//
		foreach ( $options as $option ) {
			add_filter( "option_{$option}", [ $self, 'set_option_value'], 10, 2 );
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	protected function get_option_values() {
		$option_values = [
			'wmc_wizard_type'                 => 'elegant',
			'wmc_add_login_form'              => 'false',
			'wmc_add_register_form'           => 'false',
			'wmc_add_coupon_form'             => 'true',
			'wmc_merge_billing_shipping_tabs' => 'false',
			'wmc_merge_order_payment_tabs'    => 'true',
			'wmc_add_order_review'            => 'false',
			'wmc_show_product_thumbnail'      => 'false',
			'wmc_animation'                   => 'fade',
			'wmc_orientation'                 => 'vertical',
			'wmc_remove_numbers'              => 'false',
			'wmc_spinner_color'               => Properties::FONT_COLOR,
			'wmc_scroll_to_error'             => 'yes',
			'wmc_scroll_offset'               => 30,
			'wmc_tabs_color'                  => Properties::PRIMARY_COLOR,
			'wmc_inactive_tabs_color'         => null,
			'wmc_font_color'                  => '#fff',
			'wmc_completed_tabs_color'        => '#eee',
			'wmc_completed_font_color'        => null,
			'wmc_buttons_bg_color'            => Properties::PRIMARY_COLOR,
			'wmc_buttons_font_color'          => null,
			'wmc_form_labels_color'           => null,
			'wmc_btn_next'                    => __( 'Volgende', 'siw' ),
			'wmc_btn_prev'                    => __( 'Vorige', 'siw' ),
			'wmc_btn_finish'                  => __( 'Aanmelden', 'siw' ),
			'wmc_no_account_btn'              => null,
			'wmc_coupon_label'                => __( 'Kortingscode', 'siw' ),
			//'wmc_billing_label'               => __( 'Je gegevens', 'siw' ),
			//'wmc_shipping_label'              => __( 'Informatie partnerorganisatie', 'siw' ),
			'wmc_billing_shipping_label'      => __( 'Je gegevens', 'siw' ),
			//'wmc_orderinfo_label'             => __( 'Overzicht & betalenz', 'siw' ),
			'wmc_paymentinfo_label'           => __( 'Overzicht & betalen', 'siw' ),
			//'wmc_order_review_label'          => __( 'Overzicht', 'siw' ),
			'wmc_empty_error'                 => __( 'Dit veld is verplicht', 'siw' ),
			'wmc_email_error'                 => __( 'Dit is geen geldig e-mailadres', 'siw' ),
			'wmc_phone_error'                 => __( 'Dit is geen geldig telefoonnummer', 'siw' ),
			'wmc_terms_error'                 => __( 'Je moet akkoord gaan met onze inschrijfvoorwaarden', 'siw' ),
			'wmc_add_code_footer'             => 'false',
		];

		return $option_values;
	}



	/**
	 * Undocumented function
	 *
	 * @param mixed $value
	 * @param string $option
	 *
	 * @return mixed
	 */
	public function set_option_value( $value, string $option ) {
		$option_values = $this->get_option_values();
		return $option_values[ $option ];// ?? $value;
	}
}
