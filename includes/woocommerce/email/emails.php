<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

use SIW\Data\Language;
use SIW\Properties;
use SIW\Util\CSS;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Aanpassingen t.b.v. WooCommerce e-mails
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Emails {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_email_from_name', [ $self, 'set_email_from_name' ], 10, 2 );
		add_filter( 'woocommerce_email_from_address', [ $self, 'set_email_from_address' ], 10, 2 );
		add_filter( 'wc_get_template', [ $self, 'set_header_template'], 10, 5 );
		add_filter( 'wc_get_template', [ $self, 'set_footer_template'], 10, 5 );
		//add_filter( 'woocommerce_defer_transactional_emails', '__return_true' );

		add_action( 'siw_woocommerce_email_order_table', [ $self, 'show_order_table' ] );
	}

	/** Zet naam afzender */
	public function set_email_from_name(): string{
		return Properties::NAME;
	}

	/** Zet e-mailadres afzender */
	public function set_email_from_address(): string {
		return siw_get_email_settings( 'workcamp' )->get_confirmation_mail_sender();
	}

	/** Overschrijft header-template */
	public function set_header_template( string $located, string $template_name, array $args, string $template_path, string $default_path ): string {
		if ( 'emails/email-header.php' === $template_name ) {
			$located = SIW_TEMPLATES_DIR . 'woocommerce/'. $template_name;
		}
		return $located;
	}

	/** Overschrijft footer-template */
	public function set_footer_template( string $located, string $template_name, array $args, string $template_path, string $default_path ): string {
		if ( 'emails/email-footer.php' === $template_name ) {
			$located = SIW_TEMPLATES_DIR . 'woocommerce/'. $template_name;
		}
		return $located;
	}

	/** Toont tabel met aanmeldingsgegevens */
	public function show_order_table( \WC_Order $order ) {
		$table_data = $this->get_table_data( $order );

		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="3" height="20" style="font-family:Verdana, normal; color:<?php echo CSS::CONTRAST_COLOR;?>; font-size:0.8em; font-weight:bold; border-top:thin solid <?php echo CSS::ACCENT_COLOR;?>" >
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

	/** Genereert tabelrij */
	public function show_table_row( string $label, string $value = '&nbsp;' ) {?>
		<tr>
			<td width="35%" style="font-family:Verdana, normal; color:<?php echo CSS::CONTRAST_COLOR;?>; font-size:0.8em; ">
				<?= wp_kses_post( $label ); ?>
			</td>
			<td width="5%"></td>
			<td width="50%" style="font-family:Verdana, normal; color:<?php echo CSS::CONTRAST_COLOR;?>; font-size:0.8em; font-style:italic">
				<?= wp_kses_post( $value ); ?>
			</td>
		</tr>
	<?php
	}

	/** Toont tabel-headerrij */
	public function show_table_header_row( string $label ) {?>
		<tr>
			<td width="35%" style="font-family:Verdana, normal; color:<?php echo CSS::CONTRAST_COLOR;?>; font-size:0.8em; font-weight:bold">
				<?= esc_html( $label ); ?>
			</td>
			<td width="5%">&nbsp;</td>
			<td width="50%">&nbsp;</td>
		</tr>
	<?php
	}

	/** Haalt data voor tabel op */
	protected function get_table_data( \WC_Order $order ): array {

		//Referentiegegevens
		$languages = [ '' => __( 'Selecteer een taal', 'siw' ) ] + siw_get_languages_list( Language::VOLUNTEER, Language::PLATO_CODE );
		$language_skill = siw_get_language_skill_levels();

		$table_data['application'] = $this->get_application_table_data( $order );
		$table_data['payment'] = $this->get_payment_table_data( $order );
		$table_data['customer'] = [
			'header' => __( 'Gegevens', 'siw' ),
			'rows'   => [
				[
					'label' => __( 'Persoongsgegevens', 'siw' ),
					'value' => $order->get_formatted_billing_address(),
				],
				[
					'label' => __( 'E-mailadres', 'siw' ),
					'value' => $order->get_billing_email(),
				],
				[
					'label' => __( 'Telefoonnummer', 'siw' ),
					'value' => $order->get_billing_phone(),
				],
			]
		];
		$table_data['emergency_contact'] = [
			'header' => __( 'Noodcontact', 'siw' ),
			'rows'   => [
				[
					'label' => __( 'Naam', 'siw' ),
					'value' => $order->get_meta( 'emergencyContactName' ),
				],
				[
					'label' => __( 'Telefoonnummer', 'siw' ),
					'value' => $order->get_meta( 'emergencyContactPhone' ),
				]
			]
		];
		$table_data['language'] = [
			'header' => __( 'Talenkennis', 'siw' ),
			'rows'   => [
				[
					'label' => $languages[ $order->get_meta( 'language_1' ) ] ?? '',
					'value' => $language_skill[ $order->get_meta( 'language_1_skill' ) ] ?? '',
				],
				[
					'label' => $languages[ $order->get_meta( 'language_2' ) ] ?? '',
					'value' => $language_skill[ $order->get_meta( 'language_2_skill' ) ] ?? '',
				],
				[
					'label' => $languages[ $order->get_meta( 'language_3' ) ] ?? '',
					'value' => $language_skill[ $order->get_meta( 'language_3_skill' ) ] ?? '',
				],
			]
		];
		$table_data[ 'info_for_partner'  ] = [
			'header' => __( 'Informatie voor partnerorganisatie', 'siw' ),
			'rows'   => [
				[
					'label' => __( 'Motivation', 'siw' ),
					'value' => $order->get_meta( 'motivation' ),
				],
				[
					'label' => __( 'Health issues', 'siw' ),
					'value' => $order->get_meta( 'health_issues' ),
				],
				[
					'label' => __( 'Volunteer experience', 'siw' ),
					'value' => $order->get_meta( 'volunteer_experience' ),
				],
				[
					'label' => __( 'Together with', 'siw' ),
					'value' => $order->get_meta( 'together_with' )
				],
			]
		];
		return $table_data;
	}

	/** Geeft aanmeldingsgegevens terug */
	protected function get_application_table_data( \WC_Order $order ): array {

		$application_data['header'] = __( 'Aanmelding', 'siw' );
		$application_data['rows'][] = [
			'label' => __( 'Aanmeldnummer', 'siw' ),
			'value' => $order->get_order_number(),
		];
		
		/** @var \WC_Order_Item_Product[] */
		$order_items = $order->get_items();

		$project_count = count( $order_items );
		$count = 0;
		foreach ( $order_items as $item_id => $item ) {
			$count++;
			$parent = siw_get_product( $item->get_product_id() );
			
			/* Als project niet meer bestaan alleen de gegevens bij de aanmelding tonen */
			if ( ! is_a( $parent, \WC_Product::class ) ) {
				$project_details = sprintf('%s<br/><small>Tarief: %s</small>', $item->get_name(), wc_get_order_item_meta( $item_id, Taxonomy_Attribute::TARIFF()->value ) );
			}
			else {
				$project_duration = siw_format_date_range( $parent->get_attribute( 'startdatum' ), $parent->get_attribute( 'einddatum' ), false );
				$project_details = sprintf('%s<br/><small>Projectcode: %s<br>Projectduur: %s<br/>Tarief: %s</small>', $parent->get_name(), $parent->get_sku(), $project_duration, $item[Taxonomy_Attribute::TARIFF()->value] );
			}
	
			if ( 1 === $project_count ) {
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

	/** Geeft betaalgegevens terug */
	protected function get_payment_table_data( \WC_Order $order ): array {
		$payment_data['header'] = __( 'Betaling', 'siw' );
		if ( $order->get_total() != $order->get_subtotal() ) {
			$payment_data['rows'][] = [
				'label' => __( 'Subtotaal', 'siw' ),
				'value' => $order->get_subtotal_to_display(),
			];
			
			/* Toon kortingscodes */
			foreach ( $order->get_coupons() as $coupon ) {
				$payment_data['rows'][] = [
					'label' => sprintf( __( 'Kortingscode: %s', 'siw' ), $coupon->get_code() ),
					'value' => '-' . wc_price( $coupon->get_discount() ),
				];
			}
			
			/* Toon automatische kortingen */
			foreach ( $order->get_fees() as $fee ) {
				$payment_data['rows'][] = [
					'label' => $fee->get_name(),
					'value' => wc_price( $fee->get_total() ),
				];
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
