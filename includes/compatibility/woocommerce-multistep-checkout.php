<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Util\CSS;

/**
 * Aanpassingen voor WooCommerce Multistep Checkout
 * 
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 * @see       http://woocommerce-multistep-checkout.com/documentation/
 */
class WooCommerce_Multistep_Checkout {

	/** Init */
	public static function init() {

		if ( ! is_plugin_active( 'woocommerce-multistep-checkout/woocommerce-multistep-checkout.php' ) ) {
			return;
		}

		$self = new self();

		$options = array_keys( $self->get_option_values() );
		foreach ( $options as $option ) {
			add_filter( "option_{$option}", [ $self, 'set_option_value'], 10, 2 );
		}
	}

	/** Geeft waarde voor WMC-opties terug */
	protected function get_option_values(): array {
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
			'wmc_spinner_color'               => CSS::ACCENT_COLOR,
			'wmc_scroll_to_error'             => 'yes',
			'wmc_scroll_offset'               => 30,
			'wmc_tabs_color'                  => CSS::ACCENT_COLOR,
			'wmc_inactive_tabs_color'         => null,
			'wmc_font_color'                  => CSS::BASE_COLOR,
			'wmc_completed_tabs_color'        => '#eee',
			'wmc_completed_font_color'        => null,
			'wmc_buttons_bg_color'            => CSS::ACCENT_COLOR,
			'wmc_buttons_font_color'          => null,
			'wmc_form_labels_color'           => null,
			'wmc_btn_next'                    => __( 'Volgende', 'siw' ),
			'wmc_btn_prev'                    => __( 'Vorige', 'siw' ),
			'wmc_btn_finish'                  => __( 'Aanmelden', 'siw' ),
			'wmc_no_account_btn'              => null,
			'wmc_coupon_label'                => __( 'Kortingscode', 'siw' ),
			'wmc_billing_label'               => __( 'Je gegevens', 'siw' ),
			'wmc_shipping_label'              => __( 'Informatie voor partnerorganisatie', 'siw' ),
			'wmc_billing_shipping_label'      => __( 'Je gegevens', 'siw' ),
			'wmc_orderinfo_label'             => __( 'Overzicht & betalen', 'siw' ),
			'wmc_paymentinfo_label'           => __( 'Overzicht & betalen', 'siw' ),
			'wmc_order_review_label'          => __( 'Overzicht', 'siw' ),
			'wmc_empty_error'                 => __( 'Dit veld is verplicht', 'siw' ),
			'wmc_email_error'                 => __( 'Dit is geen geldig e-mailadres', 'siw' ),
			'wmc_phone_error'                 => __( 'Dit is geen geldig telefoonnummer', 'siw' ),
			'wmc_terms_error'                 => __( 'Je moet akkoord gaan met onze inschrijfvoorwaarden', 'siw' ),
			'wmc_add_code_footer'             => 'false',
		];

		return $option_values;
	}

	/** Zet waarde van optie */
	public function set_option_value( $value, string $option ) {
		$option_values = $this->get_option_values();
		return $option_values[ $option ];
	}
}
