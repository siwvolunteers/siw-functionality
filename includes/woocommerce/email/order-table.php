<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

use SIW\Data\Plato\Language;
use SIW\Data\Plato\Language_Skill_Level;
use SIW\Facades\WooCommerce;
use SIW\WooCommerce\Product\WC_Product_Project;

trait Order_Table {

	protected function get_application_data( \WC_Order $order ): array {
		$application_data[] = [
			'label' => __( 'Aanmeldnummer', 'siw' ),
			'value' => $order->get_order_number(),
		];

		/** @var \WC_Order_Item_Product[] */
		$order_items = $order->get_items();

		$project_count = count( $order_items );
		$count = 0;
		foreach ( $order_items as $item ) {
			++$count;
			$product = WooCommerce::get_product( $item->get_product_id() );

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
				$application_data[] = [
					'label' => __( 'Project', 'siw' ),
					'value' => $project_details,
				];
			} else {
				$application_data[] = [
					// translators: %d is een geheel getal
					'label' => sprintf( __( 'Project %d', 'siw' ), $count ),
					'value' => $project_details,
				];
			}
		}
		return $application_data;
	}

	protected function get_payment_data( \WC_Order $order ): array {
		if ( $order->get_total() !== $order->get_subtotal() ) {
			$payment_data[] = [
				'label' => __( 'Subtotaal', 'siw' ),
				'value' => $order->get_subtotal_to_display(),
			];

			foreach ( $order->get_coupons() as $coupon ) {
				$payment_data[] = [
					// translators: %s is de kortingscode
					'label' => sprintf( __( 'Kortingscode: %s', 'siw' ), $coupon->get_code() ),
					'value' => '-' . wc_price( $coupon->get_discount() ),
				];
			}

			foreach ( $order->get_fees() as $fee ) {
				$payment_data[] = [
					'label' => $fee->get_name(),
					'value' => wc_price( $fee->get_total() ),
				];
			}
		}
		$payment_data[] = [
			'label' => __( 'Totaal', 'siw' ),
			'value' => $order->get_formatted_order_total(),
		];
		$payment_data[] = [
			'label' => __( 'Betaalwijze', 'siw' ),
			'value' => $order->get_payment_method_title(),
		];
		return $payment_data;
	}

	protected function get_customer_data( \WC_Order $order ): array {
		return [
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
		];
	}

	protected function get_emergency_contact_data( \WC_Order $order ): array {
		return [
			[
				'label' => __( 'Naam', 'siw' ),
				'value' => $order->get_meta( 'emergency_contact_name' ),
			],
			[
				'label' => __( 'Telefoonnummer', 'siw' ),
				'value' => $order->get_meta( 'emergency_contact_phone' ),
			],
		];
	}

	protected function get_language_data( \WC_Order $order ): array {
		return [
			[
				'label' => Language::tryFrom( $order->get_meta( 'language_1' ) ?? '' )?->label() ?? '',
				'value' => Language_Skill_Level::tryFrom( $order->get_meta( 'language_1_skill' ) ?? '' )?->label() ?? '',
			],
			[
				'label' => Language::tryFrom( $order->get_meta( 'language_2' ) ?? '' )?->label() ?? '',
				'value' => Language_Skill_Level::tryFrom( $order->get_meta( 'language_2_skill' ) ?? '' )?->label() ?? '',
			],
			[
				'label' => Language::tryFrom( $order->get_meta( 'language_3' ) ?? '' )?->label() ?? '',
				'value' => Language_Skill_Level::tryFrom( $order->get_meta( 'language_3_skill' ) ?? '' )?->label() ?? '',
			],
		];
	}
	protected function get_partner_info_data( \WC_Order $order ): array {
		return [
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
		];
	}
}
