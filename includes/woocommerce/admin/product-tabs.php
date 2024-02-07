<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Facades\WooCommerce;
use SIW\WooCommerce\Product\WC_Product_Project;

class Product_Tabs extends Base {

	#[Add_Filter( 'woocommerce_product_data_tabs' )]
	public function add_tabs( array $tabs ): array {
		$tabs['siw_description'] = [
			'label'    => __( 'Omschrijving', 'siw' ),
			'target'   => 'siw_description_product_data',
			'class'    => [],
			'priority' => 1,
		];
		$tabs['siw_extra_settings'] = [
			'label'    => __( 'Extra instellingen', 'siw' ),
			'target'   => 'siw_extra_settings_product_data',
			'class'    => [],
			'priority' => 120,
		];
		return $tabs;
	}

	#[Add_Filter( 'woocommerce_product_data_tabs', PHP_INT_MAX )]
	public function hide_tabs( array $tabs ): array {
		$tabs['general']['class'] = [ 'show_if_project' ];
		$tabs['advanced']['class'] = [ 'hide_if_project' ];
		$tabs['shipping']['class'] = [ 'hide_if_project' ];
		$tabs['linked_product']['class'] = [ 'hide_if_project' ];

		if ( ! current_user_can( 'manage_options' ) ) {
			$tabs['attribute']['class'] = [ 'hide_if_project' ];
		}
		return $tabs;
	}

	#[Add_Action( 'woocommerce_product_data_panels' )]
	public function show_extra_settings_tab() {
		global $product_object;
		$product = WooCommerce::get_product( $product_object );
		if ( null === $product ) {
			return;
		}
		?>
		<div id="siw_extra_settings_product_data" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php
				woocommerce_wp_checkbox(
					[
						'id'      => '_hidden',
						'value'   => $product->is_hidden(),
						'cbvalue' => '1',
						'label'   => __( 'Verbergen', 'siw' ),
					]
				);
				if ( ! $product->is_esc_project() ) {
					woocommerce_wp_checkbox(
						[
							'id'      => '_excluded_from_student_discount',
							'value'   => $product->is_excluded_from_student_discount(),
							'cbvalue' => '1',
							'label'   => __( 'Uitsluiten van studentenkorting', 'siw' ),
						]
					);
				}
				woocommerce_wp_text_input(
					[
						'id'          => '_custom_price',
						'value'       => $product->get_custom_price(),
						'placeholder' => $product->get_price(),
						'data_type'   => 'price',
						'label'       => __( 'Afwijkend tarief', 'siw' ),
					]
				);
				?>
			</div>
		</div>

		<?php
	}

	#[Add_Action( 'woocommerce_product_data_panels' )]
	public function show_description_tab() {
		global $product_object;
		$product = WooCommerce::get_product( $product_object );
		if ( null === $product ) {
			return;
		}
		$description = $product->get_project_description();
		// TODO: verplaatsen naar WC_Product_Project

		$topics = [
			'description'           => __( 'Beschrijving', 'siw' ),
			'work'                  => __( 'Werk', 'siw' ),
			'accomodation_and_food' => __( 'Accommodatie en maaltijden', 'siw' ),
			'location_and_leisure'  => __( 'Locatie en vrije tijd', 'siw' ),
			'partner'               => __( 'Organisatie', 'siw' ),
			'requirements'          => __( 'Vereisten', 'siw' ),
			'notes'                 => __( 'Opmerkingen', 'siw' ),
		];

		?>
		<div id="siw_description_product_data" class="panel woocommerce_options_panel wc-metaboxes-wrapper">
			<div class="options_group">
				<div class="wc-metaboxes">
				<?php
				foreach ( $topics as $topic => $title ) {
					if ( ! isset( $description[ $topic ] ) || empty( $description[ $topic ] ) ) {
						continue;
					}
					?>
					<div class="wc-metabox postbox closed">
						<h3><div class="handlediv"></div>
							<strong><?php echo esc_html( $title ); ?></strong>
						</h3>
						<div class="wc-metabox-content hidden">
							<hr>
							<p><?php echo wp_kses_post( $description[ $topic ] ); ?></p>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}

	#[Add_Action( 'woocommerce_admin_process_product_object' )]
	public function save_product_data( WC_Product_Project $product ) {
		$product->set_custom_price( sanitize_text_field( wp_unslash( $_POST['_custom_price'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$product->set_hidden( isset( $_POST['_hidden'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$product->set_excluded_from_student_discount( isset( $_POST['_excluded_from_student_discount'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	}
}
