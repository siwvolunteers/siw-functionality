<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce checkout
 * 
 * @package    SIW\WooCommerce
 * @author     Maarten Bruna
 * @copyright  2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses SIW_Properties
 * @uses SIW_Util
 */
class SIW_WC_Checkout{

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		add_action( 'woocommerce_cart_calculate_fees', [ $self, 'calculate_discounts' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'add_validation_script'] );
		add_action( 'wp_enqueue_scripts', [ $self, 'add_postcode_script' ] );

		add_filter( 'woocommerce_default_address_fields', [ $self , 'set_default_address_fields'], 10, 2 );
		add_filter( 'woocommerce_billing_fields', [ $self, 'set_billing_fields'], 10, 2 );
		add_filter( 'woocommerce_shipping_fields', '__return_empty_array' );
		add_filter( 'woocommerce_checkout_fields', [ $self, 'add_checkout_fields'] );
		add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
		add_filter( 'woocommerce_get_country_locale', [ $self, 'remove_locale_postcode_priority'] );
		add_filter( 'woocommerce_country_locale_field_selectors', [ $self, 'remove_locale_field_selectors']);

		add_filter( 'woocommerce_form_field_args', [ $self, 'add_form_field_classes' ] );
		add_filter( 'woocommerce_form_field_radio', [ $self, 'add_form_field_markup' ] );
		add_filter( 'woocommerce_form_field_checkbox', [ $self, 'add_form_field_markup' ] );
		add_action( 'woocommerce_multistep_checkout_before_order_info', [ $self, 'show_checkout_partner_fields'] );
		add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', [ $self, 'set_term_checkbox_text'] );
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );
		add_action( 'woocommerce_after_checkout_form', [ $self, 'add_terms_modal'] );
		add_action( 'woocommerce_after_checkout_validation', [ $self, 'validate_checkout_fields' ], 10, 2 );
		add_action( 'woocommerce_checkout_create_order', [ $self, 'save_checkout_fields'], 10, 2 );

		add_filter( 'wc_get_template', [ $self, 'set_checkout_templates'], 10, 5 );
	}

	/**
	 * Verwijdert JS-selectors voor update locale
	 *
	 * @param array $locale_fields
	 * @return array
	 */
	public function remove_locale_field_selectors( $locale_fields ) {
		unset( $locale_fields['address_2'] );
		unset( $locale_fields['state'] );
		return $locale_fields;
	}

	/**
	 * Verwijdert aangepaste prioriteit voor postcode
	 *
	 * @param array $locale
	 * @return array
	 */
	public function remove_locale_postcode_priority( $locale ) {
		unset( $locale['NL']['postcode'] );
		return $locale;
	}

	/**
	 * Past korting toe bij meerdere projecten
	 *
	 * @param WC_Cart $cart
	 * 
	 * @todo hoort dit wel bij de checkout?
	 */
	public function calculate_discounts( $cart ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		$cart_contents = $cart->get_cart_contents();

		$count = 0;
		foreach ( $cart_contents as $line ) {
			$count++;
			if ( 2 == $count ) {
				$discount = $line['line_total'] * SIW_Properties::DISCOUNT_SECOND_PROJECT * -0.01;
				$cart->add_fee( __( 'Korting 2e project', 'siw' ), $discount );
			}
			if ( 3 == $count ) {
				$discount = $line['line_total'] * SIW_Properties::DISCOUNT_THIRD_PROJECT * -0.01;
				$cart->add_fee( __( 'Korting 3e project', 'siw' ), $discount );
			}
			if ( 3 < $count ) {
				$discount = $line['line_total'] * SIW_Properties::DISCOUNT_THIRD_PROJECT * -0.01;
				$cart->add_fee( sprintf( __( 'Korting %de project', 'siw' ), $count ), $discount );
			}
		}
	}

	/**
	 * Voegt extra markup voor gestylde radiobuttons en checkboxes toe
	 *
	 * @param string $field
	 * @return string
	 */
	public function add_form_field_markup( $field ) {
		$field = preg_replace( '/<input(.*?)>/', '<input$1><span class="control-indicator"></span>', $field );
		return $field;
	}

	/**
	 * Voegt extra classes voor gestylde radiobuttons en checkboxes toe
	 *
	 * @param array $args
	 * @return array
	 */
	public function add_form_field_classes( $args ) {
		if ( $args['type'] == 'radio' ) {
			$args['class'][] = 'control-radio';
		}
		if ( $args['type'] == 'checkbox' ) {
			$args['class'][] = 'control-checkbox';
		}
		return $args;
	}
	
	/**
	 * Haalt checkoutvelden op
	 *
	 * @param array $checkout_fields
	 * @return array
	 */
	protected function get_checkout_fields( $checkout_fields = [] ) {
		/**
		 * Extra checkout-velden
		 *
		 * @param array $checkout_fields
		 */
		$checkout_fields = apply_filters( 'siw_checkout_fields', $checkout_fields );
		return $checkout_fields;
	}

	/**
	 * Haalt secties voor checkoutvelden op
	 *
	 * @return array
	 */
	protected function get_checkout_sections() {
		$checkout_sections = [];
		/**
		 * Extra secties met checkout-velden
		 *
		 * @param array $checkout_sections
		 */
		$checkout_sections = apply_filters( 'siw_checkout_sections', $checkout_sections );
		return $checkout_sections;
	}

	/**
	 * Toont de extra checkoutvelden
	 *
	 * @param WC_Checkout $checkout
	 */
	public function show_checkout_partner_fields( $checkout ) {

		$checkout_sections = $this->get_checkout_sections();
		$checkout_fields = $this->get_checkout_fields();
		?>
		<h1><?php esc_html_e( 'Informatie voor partner', 'siw' );?></h1>
		<div class="woocommerce-extra-fields">
			<?php foreach ( $checkout_sections as $section => $header ) :?>
			<div id="<?= esc_attr( $section );?>">
				<h3><?= esc_html( $header );?></h3>
				<?php
				foreach ( $checkout_fields[ $section ] as $key => $field ) {
					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
				}
			?>
			</div>
			<?php endforeach ?>
		</div>
		<?php
	}

	/**
	 * Slaat de extra checkoutvelden op
	 *
	 * @param WC_Order $order
	 * @param array $data
	 */
	public function save_checkout_fields( $order, $data ) {
		
		$checkout_fields = $this->get_checkout_fields();

		foreach ( $checkout_fields as $section => $fields ) {
			foreach ( $fields as $key => $field ) {
				if ( isset( $data[ $key ] ) ) {
					$order->update_meta_data( $key, $data[ $key ] );
				}
			}
		}
		if ( ! empty( $data['terms'] ) ) {
			$order->update_meta_data( '_terms', $data['terms'] );
		}
	}
	
	/**
	 * Past de volgorde van de adresvelden aan
	 *
	 * @param array $address_fields
	 * @return array
	 */
	public function set_default_address_fields( $address_fields ) {
		/**
		 * Volgorde van adresvelden
		 *
		 * @param array $address_fields
		 */
		$address_fields = apply_filters( 'siw_address_fields', $address_fields);
		return $address_fields;
	}

	/**
	 * Zet de classes voor de billing velden
	 *
	 * @param array $billing_fields
	 * @param array $country
	 * @return array
	 */
	public function set_billing_fields( $billing_fields, $country ) {
		$billing_fields['billing_phone']['class'] = ['form-row-first'];
		$billing_fields['billing_email']['class'] = ['form-row-last'];
		return $billing_fields;
	}

	/**
	 * Voegt de extra checkoutvelden toe
	 *
	 * @param array $checkout_fields
	 * @return array
	 */
	public function add_checkout_fields( $checkout_fields ) {
		$checkout_fields = $this->get_checkout_fields( $checkout_fields );
		return $checkout_fields;
	}

	/**
	 * Past link naar voorwaarden-modal aan
	 *
	 * @param string $text
	 * @return string
	 */
	public function set_term_checkbox_text( $text ) {
		$link = sprintf( '<a data-toggle="modal" href="#" data-target="#siw-page-%s-modal">%s</a>', wc_terms_and_conditions_page_id(), __( 'inschrijfvoorwaarden', 'siw' ) );
		$text = sprintf(__( 'Ik heb de %s gelezen en ga akkoord', 'siw' ), $link );
		return $text;
	}

	/**
	 * Voegt html voor voorwaarden-modal toe
	 *
	 * @param WC_Checkout $checkout
	 * @return void
	 */
	public function add_terms_modal( $checkout ) {
		echo SIW_Formatting::generate_modal( wc_terms_and_conditions_page_id() );
	}

	/**
	 * Voegt extra client-side validatie toe
	 * 
	 * - Postcode
	 * - Datum
	 */
	public function add_validation_script() {
		$inline_script = "$.validator.setDefaults({
			errorPlacement: function( error, element ) {
				error.appendTo( element.parents( 'p' ) );
			}
		});";

		$validator = "$.validator.addMethod( '%s', function( value, element ) {
			return this.optional( element ) || %s.test( value );
		}, '%s' );";
		
		/* Datumvalidatie */
		$inline_script .= sprintf(
			$validator,
			'dateNL',
			SIW_Util::get_regex( 'date' ),
			esc_html__( 'Dit is geen geldige datum.', 'siw' )
		);
		/* Postcodevalidatie*/
		$inline_script .= sprintf(
			$validator,
			'postalcodeNL',
			SIW_Util::get_regex( 'postal_code' ),
			esc_html__( 'Dit is geen geldige postcode.', 'siw' )
		);
		wp_add_inline_script( 'jquery-validate', "(function( $ ) {" . $inline_script . "})( jQuery );" ); //TODO:format-functie voor anonymous jQuery
	}

	/**
	 * Voegt inline script voor postcode lookup toe
	 * 
	 * @todo inline script bij siw-postcode ipv wc-checkout
	 */
	public function add_postcode_script() {
		$inline_script = "
			$( document ).on( 'change', '#billing_postcode, #billing_housenumber', function() {
				siwPostcodeLookupFromForm( '#billing_postcode', '#billing_housenumber', '#billing_address_1', '#billing_city' );
				return false;
			});";
	
		wp_add_inline_script( 'wc-checkout', "(function( $ ) {" . $inline_script . "})( jQuery );" );//TODO:format-functie voor anonymous jQuery
	}

	/**
	 * Voert validatie voor extra checkout velden uit
	 *
	 * @param array $data
	 * @param WP_Error $errors
	 * @return void
	 * 
	 * @todo minimum/maximum-leeftijd van project gebruiken
	 */
	public function validate_checkout_fields( $data, $errors ) {

		$dob = $data['billing_dob'];
		if ( false === (bool) preg_match( SIW_Util::get_regex('date'), $dob ) ) {
			$errors->add( 'validation', sprintf( __( '%s bevat geen geldige datum.', 'siw' ), '<strong>' . esc_html__( 'Geboortedatum','siw' ) . '</strong>' ) );
		}
		else {
			$min_age = 14; //TODO: property / projecteigenschap
			$age = SIW_Util::calculate_age( $dob );
			if ( $age < $min_age ) {
				$errors->add( 'validation', sprintf( __( 'De minimumleeftijd voor deelname is %s jaar.', 'siw' ), '<strong>' . esc_html( $min_age ) . '</strong>' ) );
			}
		}
	}
	
	/**
	 * Overschrijft templates
	 *
	 * @param string $located
	 * @param array $template_name
	 * @param string $args
	 * @param string $template_path
	 * @param string $default_path
	 * @return string
	 */
	public function set_checkout_templates( $located, $template_name, $args, $template_path, $default_path ) {
		if ( 'checkout/terms.php' == $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		if ( 'checkout/payment-method.php' == $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		return $located;
	}
}