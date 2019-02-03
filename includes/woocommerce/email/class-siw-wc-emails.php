<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Aanpassingen t.b.v. WooCommerce e-mails
 *
 * @package   SIW\WooCommerce
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

class SIW_WC_Emails {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_email_from_name', [ $self, 'set_email_from_name' ], 10, 2 );
		add_filter( 'woocommerce_email_from_address', [ $self, 'set_email_from_address' ], 10, 2 );
		add_filter( 'wc_get_template', [ $self, 'set_header_template'], 10, 5 );
		add_filter( 'wc_get_template', [ $self, 'set_footer_template'], 10, 5 );
		//add_filter( 'woocommerce_defer_transactional_emails', '__return_true' );

		add_action( 'siw_woocommerce_email_order_table', [ $self, 'show_order_table' ] );
	}

	/**
	 * Zet naam afzender
	 *
	 * @param string $from_name
	 * @param WC_Email $email
	 * @return string
	 */
	public function set_email_from_name( $from_name, $email ) {
		$from_name = SIW_Properties::get('name');
		return $from_name;
	}

	/**
	 * Zet e-mailadres afzender
	 *
	 * @param string $from_address
	 * @param WC_Email $email
	 * @return string
	 */
	public function set_email_from_address( $from_address, $email ) {
		$from_address = siw_get_setting( 'workcamp_application_email_sender' );
		return $from_address;
	}

	/**
	 * Overschrijft header-template
	 *
	 * @param string $located
	 * @param array $template_name
	 * @param string $args
	 * @param string $template_path
	 * @param string $default_path
	 * @return string
	 */
	public function set_header_template( $located, $template_name, $args, $template_path, $default_path ) {
		if ( 'emails/email-header.php' == $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		return $located;
	}

	/**
	 * Overschrijft footer-template
	 *
	 * @param string $located
	 * @param array $template_name
	 * @param string $args
	 * @param string $template_path
	 * @param string $default_path
	 * @return string
	 */
	public function set_footer_template( $located, $template_name, $args, $template_path, $default_path ) {
		if ( 'emails/email-footer.php' == $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		return $located;
	}


	/**
	 * Toont tabel met aanmeldingsgegevens
	 *
	 * @param WC_Order $order
	 */
	public function show_order_table( $order ) {
		$table_data = $this->get_table_data( $order );

		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="3" height="20" style="font-family:Verdana, normal; color:<?= SIW_Properties::get('font_color');?>; font-size:0.8em; font-weight:bold; border-top:thin solid <?= SIW_Properties::get('primary_color');?>" >
					&nbsp;
				</td>
			</tr>
			<?php
			foreach ( $table_data as $section ) {
				$this->show_table_header_row( $section['header'] );
				foreach ( $section['rows'] as $row ) {
					if ( ! empty( $row['label'] ) && ! empty( $row['value'] ) ) {
						$this->show_table_row( $row['label'], $row['value'] );
					}
				}
				$this->show_table_row( '&nbsp;', '&nbsp;');
			}
			?>
		</table>
	<?php
	}

	/**
	 * Genereert tabelrij
	 *
	 * @param string $label
	 * @param string $value
	 */
	public function show_table_row( $label, $value = '&nbsp;' ) {?>
		<tr>
			<td width="35%" style="font-family:Verdana, normal; color:<?= SIW_Properties::get('font_color');?>; font-size:0.8em; ">
				<?= wp_kses_post( $label ); ?>
			</td>
			<td width="5%"></td>
			<td width="50%" style="font-family:Verdana, normal; color:<?= SIW_Properties::get('font_color');?>; font-size:0.8em; font-style:italic">
				<?= wp_kses_post( $value ); ?>
			</td>
		</tr>
	<?php
	}

	/**
	 * Toont tabel-headerrij
	 *
	 * @param string $label
	 */
	public function show_table_header_row( $label ) {?>
		<tr>
			<td width="35%" style="font-family:Verdana, normal; color:<?= SIW_Properties::get('font_color');?>; font-size:0.8em; font-weight:bold">
				<?= esc_html( $label ); ?>
			</td>
			<td width="5%">&nbsp;</td>
			<td width="50%">&nbsp;</td>
		</tr>
	<?php
	}

	/**
	 * Haalt data voor tabel op
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	protected function get_table_data( $order ) {
		/* Ophalen order gegevens */
		$order_data = siw_get_order_data( $order );

		$table_data['application'] = $this->get_application_table_data( $order );
		$table_data['payment'] = $this->get_payment_table_data( $order );
		$table_data['customer'] = [
			'header' => __( 'Persoonsgegevens', 'siw' ),
			'rows'   => [
				[
					'label' => __( 'Naam', 'siw' ),
					'value' => $order_data['full_name']
				],
				[
					'label' => __( 'Geboortedatum', 'siw' ),
					'value' => $order_data['date_of_birth'],
				],
				[
					'label' => __( 'Geslacht', 'siw' ),
					'value' => $order_data['gender']
				],
				[
					'label' => __( 'Nationaliteit', 'siw' ),
					'value' => $order_data['nationality'],
				],
				[
					'label' => __( 'Adres', 'siw' ),
					'value' => $order_data['address'],
				],
				[
					'label' => __( 'E-mailadres', 'siw' ),
					'value' => $order_data['email'],
				],
				[
					'label' => __( 'Telefoonnummer', 'siw' ),
					'value' => $order_data['phone'],
				],
			]
		];
		$table_data['emergency_contact'] = [
			'header' => __( 'Noodcontact', 'siw' ),
			'rows'   => [
				[
					'label' => __( 'Naam', 'siw' ),
					'value' => $order_data['emergency_contact_name']
				],
				[
					'label' => __( 'Telefoonnummer', 'siw' ),
					'value' => $order_data['emergency_contact_phone'],
				]
			]
		];
		$table_data['language'] = [
			'header' => __( 'Talenkennis', 'siw' ),
			'rows'   => [
				[
					'label' => $order_data['language_1'],
					'value' => $order_data['language_1_skill'],
				],
				[
					'label' => $order_data['language_2'],
					'value' => $order_data['language_2_skill'],
				],
				[
					'label' => $order_data['language_3'],
					'value' => $order_data['language_3_skill'],
				],
			]
		];
		$table_data[ 'info_for_partner'  ] = [
			'header' => __( 'Informatie voor partnerorganisatie', 'siw' ),
			'rows'   => [
				[
					'label' => __( 'Motivation', 'siw' ),
					'value' => $order_data['motivation'],
				],
				[
					'label' => __( 'Health issues', 'siw' ),
					'value' => $order_data['health_issues']
				],
				[
					'label' => __( 'Volunteer experience', 'siw' ),
					'value' => $order_data['volunteer_experience']
				],
				[
					'label' => __( 'Together with', 'siw' ),
					'value' => $order_data['together_with']
				],
			]
		];
		return $table_data;
	}

	/**
	 * Geeft aanmeldingsgegevens terug
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	protected function get_application_table_data( $order ) {

		$application_data['header'] = __( 'Aanmelding', 'siw' );
		$application_data['rows'][] = [
			'label' => __( 'Aanmeldnummer', 'siw' ),
			'value' => $order->get_order_number(),
		];

		$order_items = $order->get_items();
		$project_count = count( $order_items );
		$count = 0;
		foreach ( $order_items as $item_id => $item ) {
			$count++;
			$parent = wc_get_product( $item->get_product_id() );
			
			/* Als project niet meer bestaan alleen de gegevens bij de aanmelding tonen*/
			if ( false == $parent ) {
				$project_details = sprintf('%s<br/><small>Tarief: %s</small>', $item->get_name(), wc_get_order_item_meta( $item_id )['pa_tarief'][0] );
			}
			else {
				$project_duration = SIW_Formatting::format_date_range( $parent->get_attribute( 'startdatum' ), $parent->get_attribute( 'einddatum' ), false );
				$project_details = sprintf('%s<br/><small>Projectcode: %s<br>Projectduur: %s<br/>Tarief: %s</small>', $parent->get_name(), $parent->get_sku(), $project_duration, $item['pa_tarief'] );
			}
	
			if ( 1 == $project_count ) {
				$application_data['rows'][] = [
					'label' => __( 'Project', 'siw' ),
					'value' => $project_details,
				];
			}
			else {
				$application_data['rows'][] = [
					'label' => sprintf( __( 'Project %d', 'siw' ), $count ),
					'value' => $project_details,
				];
			}
			
		}
		return $application_data;
	}

	/**
	 * Geeft betaalgegevens terug
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	protected function get_payment_table_data( $order ) {
		$payment_data['header'] = __( 'Betaling', 'siw' );
		if ( $order->get_total() != $order->get_subtotal() ) {
			$payment_data['rows'][] = [
				'label' => __( 'Subtotaal', 'siw' ),
				'value' => $order->get_subtotal_to_display(),
			];
			/* Toon kortingscodes */
			if ( $coupons = $order->get_items( 'coupon' ) ) {
				foreach ( $coupons as $coupon ) {
					$payment_data['rows'][] = [
						'label' => sprintf( __( 'Kortingscode: %s', 'siw' ), $coupon->get_code() ),
						'value' => '-' . wc_price( $coupon->get_discount() ),
					];
				}
			}
			/* Toon automatische kortingen */
			if ( $fees = $order->get_fees() ) {
				foreach ( $fees as $id => $fee ) {
					$payment_data['rows'][] = [
						'label' => $fee->get_name(),
						'value' => wc_price( $fee->get_total() ),
					];
				}
			}
		}
		$payment_data['rows'][] = [
			'label' => __( 'Totaal', 'siw' ),
			'value' => $order->get_formatted_order_total(),
		];
		$payment_data['rows'][] = [
			'label' => __( 'Betaalwijze', 'siw' ),
			'value' => $order->get_payment_method_title(),
		];
		return $payment_data;
	}
}