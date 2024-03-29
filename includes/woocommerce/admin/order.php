<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Gender;
use SIW\Data\Nationality;
use SIW\Facades\WooCommerce;

class Order extends Base {

	protected function get_checkout_sections(): array {
		$checkout_sections = siw_get_data( 'workcamps/checkout-sections' );
		return $checkout_sections;
	}

	protected function get_checkout_section( string $section ): ?string {
		$checkout_sections = $this->get_checkout_sections();

		if ( isset( $checkout_sections[ $section ] ) ) {
			return $checkout_sections[ $section ];
		}
		return null;
	}

	protected function get_checkout_fields( string $section = '' ): array {
		$checkout_fields = siw_get_data( 'workcamps/checkout-fields' );

		if ( ! empty( $section ) && isset( $checkout_fields[ $section ] ) ) {
			$checkout_fields = $checkout_fields[ $section ];
		}
		return $checkout_fields;
	}

	#[Add_Filter( 'woocommerce_admin_billing_fields' )]
	public function set_admin_billing_fields( array $fields ): array {
		// TODO:styling

		// TODO:wp_parse_args_recursive + unset
		$billing_fields = [
			'gender'      => [
				'label'   => __( 'Geslacht', 'siw' ),
				'show'    => false,
				'type'    => 'select',
				'options' => Gender::list(),
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
				'options' => Nationality::list(),
			],
			'email'       => $fields['email'],
			'phone'       => $fields['phone'],
		];
		return $billing_fields;
	}

	#[Add_Filter( 'woocommerce_localisation_address_formats' )]
	public function set_localisation_address_format( array $address_formats ): array {
		$address_formats['default'] = "{name}\n{dob}\n{gender}\n{nationality}";
		return $address_formats;
	}

	#[Add_Filter( 'woocommerce_formatted_address_replacements' )]
	public function set_formatted_address_replacements( array $replace, array $args ): array {
		$replace['{gender}'] = $args['gender'] ?? '';
		$replace['{nationality}'] = $args['nationality'] ?? '';
		$replace['{dob}'] = $args['dob'] ? siw_format_date( $args['dob'] ) : '';
		return $replace;
	}

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

	#[Add_Filter( 'woocommerce_order_formatted_billing_address' )]
	public function format_billing_address( array $address, \WC_Order $order ): array {
		$address['dob'] = $order->get_meta( '_billing_dob' );
		$address['gender'] = Gender::tryFrom( $order->get_meta( '_billing_gender' ) )?->label() ?? '';
		$address['nationality'] = Nationality::tryFrom( $order->get_meta( '_billing_nationality' ) )?->label() ?? '';
		return $address;
	}

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

	#[Add_Action( 'woocommerce_admin_order_data_after_billing_address' )]
	public function show_language_meta( \WC_Order $order ) {
		$this->show_section( $order, 'language' );
	}

	/**
	 * Toon extra gegevens van aanmelding
	 *
	 * - Info voor partner
	 * - Noodcontact
	 */
	#[Add_Action( 'woocommerce_admin_order_data_after_order_details' )]
	public function show_order_meta( \WC_Order $order ) {
		$this->show_terms( $order );
		$this->show_section( $order, 'info_for_partner', true );
		$this->show_section( $order, 'emergency_contact' );
	}

	#[Add_Action( 'woocommerce_process_shop_order_meta' )]
	public function process_order_meta( int $post_id, \WP_Post $post ) {

		$custom_fields = $this->get_checkout_fields();
		$order = WooCommerce::get_order( $post_id );

		foreach ( $custom_fields as $group => $fields ) {
			foreach ( $fields as $key => $field ) {
				if ( ! empty( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					$order->update_meta_data( $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				}
			}
		}
		$order->save();
	}

	#[Add_Action( 'admin_init', 20 )]
	public function manage_admin_columns() {
		if ( ! class_exists( '\MBAC\Post' ) ) {
			return;
		}
		new Order_Columns( 'shop_order', [] );
	}

	#[Add_Action( 'add_meta_boxes', PHP_INT_MAX )]
	public function remove_meta_boxes() {
		remove_meta_box( 'postcustom', 'shop_order', 'normal' );
		remove_meta_box( 'woocommerce-order-downloads', 'shop_order', 'normal' );
	}
}

