<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Language;
use SIW\Data\Plato\Language_Skill_Level;
use SIW\Properties;
use SIW\Util\CSS;
use SIW\WooCommerce\Product\WC_Product_Project;

class Emails extends Base {

	#[Add_Filter( 'woocommerce_email_from_name' )]
	public function set_email_from_name(): string {
		return Properties::NAME;
	}

	#[Add_Filter( 'woocommerce_email_from_address' )]
	public function set_email_from_address(): string {
		return siw_get_email_settings( 'workcamp' )->get_confirmation_mail_sender();
	}

	#[Add_Action( 'siw_woocommerce_email_order_table' )]
	public function show_order_table( \WC_Order $order ) {
		$table_data = $this->get_table_data( $order );

		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="3" height="20" style="font-family:Verdana, normal; color:<?php echo esc_attr( CSS::CONTRAST_COLOR ); ?>; font-size:0.8em; font-weight:bold; border-top:thin solid <?php echo esc_attr( CSS::CONTRAST_COLOR ); ?>" >
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
				$this->show_table_row( '&nbsp;', '&nbsp;' );
			}
			?>
		</table>
		<?php
	}

	public function show_table_row( string $label, string $value = '&nbsp;' ) {
		?>
		<tr>
			<td width="35%" style="font-family:Verdana, normal; color:<?php echo esc_attr( CSS::CONTRAST_COLOR ); ?>; font-size:0.8em; ">
				<?php echo wp_kses_post( $label ); ?>
			</td>
			<td width="5%"></td>
			<td width="50%" style="font-family:Verdana, normal; color:<?php echo esc_attr( CSS::CONTRAST_COLOR ); ?>; font-size:0.8em; font-style:italic">
				<?php echo wp_kses_post( $value ); ?>
			</td>
		</tr>
		<?php
	}

	public function show_table_header_row( string $label ) {
		?>
		<tr>
			<td width="35%" style="font-family:Verdana, normal; color:<?php echo esc_attr( CSS::CONTRAST_COLOR ); ?>; font-size:0.8em; font-weight:bold">
				<?php echo esc_html( $label ); ?>
			</td>
			<td width="5%">&nbsp;</td>
			<td width="50%">&nbsp;</td>
		</tr>
		<?php
	}

	protected function get_table_data( \WC_Order $order ): array {
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
			],
		];
		$table_data['emergency_contact'] = [
			'header' => __( 'Noodcontact', 'siw' ),
			'rows'   => [
				[
					'label' => __( 'Naam', 'siw' ),
					'value' => $order->get_meta( 'emergency_contact_name' ),
				],
				[
					'label' => __( 'Telefoonnummer', 'siw' ),
					'value' => $order->get_meta( 'emergency_contact_phone' ),
				],
			],
		];
		$table_data['language'] = [
			'header' => __( 'Talenkennis', 'siw' ),
			'rows'   => [
				[
					'label' => Language::try_from_plato_code( $order->get_meta( 'language_1' ) ?? '' )?->label() ?? '',
					'value' => Language_Skill_Level::tryFrom( $order->get_meta( 'language_1_skill' ) ?? '' )?->label() ?? '',
				],
				[
					'label' => Language::try_from_plato_code( $order->get_meta( 'language_2' ) ?? '' )?->label() ?? '',
					'value' => Language_Skill_Level::tryFrom( $order->get_meta( 'language_2_skill' ) ?? '' )?->label() ?? '',
				],
				[
					'label' => Language::try_from_plato_code( $order->get_meta( 'language_3' ) ?? '' )?->label() ?? '',
					'value' => Language_Skill_Level::tryFrom( $order->get_meta( 'language_3_skill' ) ?? '' )?->label() ?? '',
				],
			],
		];
		$table_data['info_for_partner'] = [
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
					'value' => $order->get_meta( 'together_with' ),
				],
			],
		];
		return $table_data;
	}

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
		foreach ( $order_items as $item ) {
			++$count;
			$product = siw_get_product( $item->get_product_id() );

			/* Als project niet meer bestaan alleen de gegevens bij de aanmelding tonen */
			if ( ! is_a( $product, WC_Product_Project::class ) ) {
				$project_details = $item->get_name();
			} else {
				$project_duration = siw_format_date_range( $product->get_start_date(), $product->get_end_date(), false );
				$project_details = sprintf(
					'%s<br/><small>Projectcode: %s<br>Projectduur: %s</small>',
					$product->get_name(),
					$product->get_sku(),
					$project_duration
				);
			}

			if ( 1 === $project_count ) {
				$application_data['rows'][] = [
					'label' => __( 'Project', 'siw' ),
					'value' => $project_details,
				];
			} else {
				$application_data['rows'][] = [
					// translators: %d is een geheel getal
					'label' => sprintf( __( 'Project %d', 'siw' ), $count ),
					'value' => $project_details,
				];
			}
		}
		return $application_data;
	}

	protected function get_payment_table_data( \WC_Order $order ): array {
		$payment_data['header'] = __( 'Betaling', 'siw' );
		if ( $order->get_total() !== $order->get_subtotal() ) {
			$payment_data['rows'][] = [
				'label' => __( 'Subtotaal', 'siw' ),
				'value' => $order->get_subtotal_to_display(),
			];

			foreach ( $order->get_coupons() as $coupon ) {
				$payment_data['rows'][] = [
					// translators: %s is de kortingscode
					'label' => sprintf( __( 'Kortingscode: %s', 'siw' ), $coupon->get_code() ),
					'value' => '-' . wc_price( $coupon->get_discount() ),
				];
			}

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
