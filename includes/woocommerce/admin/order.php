<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\Data\Gender;

/**
 * Aanpassing aan admin t.b.v. aanmeldingen
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 *
 * @todo      splitsen in Order en Admin_Order + refactor enzo
 */
class Order {

	/** Init */
	public static function init() {
		$self = new self();

		add_action( 'add_meta_boxes', [ $self, 'remove_meta_boxes' ], PHP_INT_MAX );

		add_action( 'woocommerce_admin_order_data_after_order_details', [ $self, 'show_order_meta' ] );
		add_action( 'woocommerce_admin_order_data_after_billing_address', [ $self, 'show_language_meta' ] );
		add_filter( 'woocommerce_order_formatted_billing_address', [ $self, 'format_billing_address' ], 10, 2 );
		add_filter( 'woocommerce_localisation_address_formats', [ $self, 'set_localisation_address_format' ] );
		add_filter( 'woocommerce_formatted_address_replacements', [ $self, 'set_formatted_address_replacements' ], 10, 2 );
		add_filter( 'woocommerce_admin_billing_fields', [ $self, 'set_admin_billing_fields' ] );
		add_action( 'woocommerce_process_shop_order_meta', [ $self, 'process_order_meta' ], 10, 2 );

		add_action( 'admin_init', [ $self, 'manage_admin_columns' ], 20 );
	}

	/** Geeft secties met velden terug */
	protected function get_checkout_sections(): array {
		$checkout_sections = siw_get_data( 'workcamps/checkout-sections' );
		return $checkout_sections;
	}

	/** Geeft velden van 1 sectie terug */
	protected function get_checkout_section( string $section ): ?string {
		$checkout_sections = $this->get_checkout_sections();

		if ( isset( $checkout_sections[ $section ] ) ) {
			return $checkout_sections[ $section ];
		}
		return null;
	}

	/** Geeft checkout velden terug */
	protected function get_checkout_fields( string $section = '' ): array {
		$checkout_fields = siw_get_data( 'workcamps/checkout-fields' );

		if ( ! empty( $section ) && isset( $checkout_fields[ $section ] ) ) {
			$checkout_fields = $checkout_fields[ $section ];
		}
		return $checkout_fields;
	}

	/** Undocumented function */
	public function set_admin_billing_fields( array $fields ): array {
		// TODO:styling

		// TODO:wp_parse_args_recursive + unset
		$billing_fields = [
			'gender'      => [
				'label'   => __( 'Geslacht', 'siw' ),
				'show'    => false,
				'type'    => 'select',
				'options' => siw_get_enum_array( Gender::cases() ),
			],
			'first_name'  => $fields['first_name'],
			'last_name'   => $fields['last_name'],
			'dob'         => [
				'label' => __( 'Geboortedatum', 'siw' ),
				'show'  => false,
				'type'  => 'date',
			],
			'nationality' => [
				'label'   => __( 'Nationaliteit', 'siw' ),
				'show'    => false,
				'type'    => 'select',
				'options' => siw_get_nationalities(),
			],
			'email'       => $fields['email'],
			'phone'       => $fields['phone'],
		];
		return $billing_fields;
	}

	/** Zet het gelokaliseerde adresformaat (van Nederland) */
	public function set_localisation_address_format( array $address_formats ): array {
		$address_formats['default'] = "{name}\n{dob}\n{gender}\n{nationality}";
		return $address_formats;
	}

	/** Undocumented function */
	public function set_formatted_address_replacements( array $replace, array $args ): array {
		$replace['{gender}'] = $args['gender'] ?? '';
		$replace['{nationality}'] = $args['nationality'] ?? '';
		$replace['{dob}'] = $args['dob'] ? siw_format_date( $args['dob'] ) : '';
		return $replace;
	}

	/** Toont sectie met velden */
	protected function show_section( \WC_Order $order, string $section, bool $edit = false ) {
		?>
		<br class="clear" />
		<h4>
			<?php
			echo esc_html( $this->get_checkout_section( $section ) );
			if ( $edit ) {
				echo '<a href="#" class="edit_address">' . esc_html__( 'Bewerken', 'siw' ) . '</a>';
			}
			?>
		</h4>
		<div class="address">
		<?php
			$fields = $this->get_checkout_fields( $section );
		foreach ( $fields as $key => $field ) {
			$field['id'] = $key;
			$this->show_field_value( $order, $field );
		}
		?>
		</div>
		<div class="edit_address">
		<?php
			$fields = $this->get_checkout_fields( $section );
		foreach ( $fields as $key => $field ) {
			$field['id'] = $key;
			$this->show_field_input( $order, $field );
		}
		?>
		</div>
		<?php
	}

	/** Toont waarde van veld */
	protected function show_field_value( \WC_Order $order, array $field ) {

		switch ( $field['type'] ) {
			case 'select':
			case 'radio':
				$field_value = $order->get_meta( $field['id'] );
				$field_options = $field['options'];
				if ( $field_value && $field_options[ $field_value ] ) {
					$field_value = $field_options[ $field_value ];
				}
				break;
			default:
				$field_value = $order->get_meta( $field['id'] );
				break;
		}

		if ( $field_value ) {
			echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . wp_kses_post( $field_value ) . '</p>';
		}
	}

	/** Toont inputveld */
	protected function show_field_input( \WC_Order $order, array $field ) {
		unset( $field['class'] );
		$field['value'] = $order->get_meta( $field['id'] );
		$field['desc_tip'] = true;
		switch ( $field['type'] ) {
			case 'select':
				woocommerce_wp_select( $field );
				break;
			case 'radio':
				$field['style'] = 'width:16px';
				woocommerce_wp_radio( $field );
				break;
			case 'checkbox':
				woocommerce_wp_checkbox( $field );
				break;
			case 'textarea':
				$field['wrapper_class'] = 'form-field-wide';
				woocommerce_wp_textarea_input( $field );
				break;
			default:
				$field['wrapper_class'] = 'form-field-wide';
				woocommerce_wp_text_input( $field );
				break;
		}
	}

	/** Formatteert factuuradres */
	public function format_billing_address( array $address, \WC_Order $order ): array {
		$address['dob'] = $order->get_meta( '_billing_dob' );
		$address['gender'] = Gender::tryFrom( $order->get_meta( '_billing_gender' ) )?->label() ?? '';
		$address['nationality'] = siw_get_nationalities() [ $order->get_meta( '_billing_nationality' ) ] ?? '';
		return $address;
	}

	/** Toont of gebruiker akkoord met inschrijfvoorwaarden is gegaan */
	public function show_terms( \WC_Order $order ) {
		echo '<br class="clear" />';

		woocommerce_wp_checkbox(
			[
				'id'                => '_terms',
				'value'             => $order->get_meta( '_terms' ),
				'cbvalue'           => '1',
				'label'             => __( 'Akkoord met inschrijfvoorwaarden', 'siw' ),
				'style'             => 'width:16px',
				'custom_attributes' => [
					'readonly' => 'readonly',
					'disabled' => 'disabled',
				],
			]
		);
	}

	/** Toont taalgegevens */
	public function show_language_meta( \WC_Order $order ) {
		$this->show_section( $order, 'language' );
	}

	/**
	 * Toon extra gegevens van aanmelding
	 *
	 * - Info voor partner
	 * - Noodcontact
	 */
	public function show_order_meta( \WC_Order $order ) {
		$this->show_terms( $order );
		$this->show_section( $order, 'info_for_partner', true );
		$this->show_section( $order, 'emergency_contact' );
	}

	/** Slaat extra checkout velden op */
	public function process_order_meta( int $post_id, \WP_Post $post ) {

		$custom_fields = $this->get_checkout_fields();
		$order = wc_get_order( $post_id );

		foreach ( $custom_fields as $group => $fields ) {
			foreach ( $fields as $key => $field ) {
				if ( ! empty( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					$order->update_meta_data( $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				}
			}
		}
		$order->save();
	}

	/** Voegt extra admin columns toe */
	public function manage_admin_columns() {
		if ( ! class_exists( '\MBAC\Post' ) ) {
			return;
		}
		new Order_Columns( 'shop_order', [] );
	}

	/** Verwijdert overbodige meta-boxes */
	public function remove_meta_boxes() {
		remove_meta_box( 'postcustom', 'shop_order', 'normal' );
		remove_meta_box( 'woocommerce-order-downloads', 'shop_order', 'normal' );
	}
}

