<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\WooCommerce\Product\WC_Product_Project;

/**
 * Tabs voor Groepsprojecten
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 */
class Product_Tabs {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_product_data_tabs', [ $self, 'add_tabs'] );
		add_filter( 'woocommerce_product_data_tabs', [ $self, 'hide_tabs'], PHP_INT_MAX );
		
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_description_tab'] );
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_extra_settings_tab'] );

		add_action( 'woocommerce_admin_process_product_object', [ $self, 'save_product_data'] );
	}

	/** Voegt extra product tabs toe */
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

	/** Verbergt overbodige product tabs */
	public function hide_tabs( array $tabs ): array {
		$tabs['general']['class'] = ['show_if_project'];
		$tabs['advanced']['class'] = ['hide_if_project'];
		$tabs['shipping']['class'] = ['hide_if_project'];
		$tabs['linked_product']['class'] = ['hide_if_project'];

		if ( ! current_user_can( 'manage_options' ) ) {
			//$tabs['attribute']['class'] = ['hide_if_project'];
		}
		return $tabs;
	}

	/** Toont tab met extra opties */
	public function show_extra_settings_tab() {
		global $product_object;
		$product = \siw_get_product( $product_object );
		if ( null == $product ) {
			return;
		}
		?>
		<div id="siw_extra_settings_product_data" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php
				//Alleen tonen als het project een afbeelding uit Plato heeft of als de optie al aangevinkt is
				if ( $product->has_plato_image() || $product->use_stockfoto() ) {
					woocommerce_wp_checkbox(
						[
							'id'          => 'use_stockphoto',
							'value'       => $product->use_stockfoto(),
							'cbvalue'     => '1',
							'label'       => __( 'Stockfoto gebruiken', 'siw' ),
							'description' => __( 'Bijvoorbeeld indien projectfoto niet geschikt is.', 'siw' ),
						]
					);
				}
				woocommerce_wp_checkbox(
					[
						'id'      => '_hidden',
						'value'   => $product->is_hidden(),
						'cbvalue' => '1',
						'label'   => __( 'Verbergen', 'siw' ),
					]
				);
				woocommerce_wp_text_input(
					[
						'id'          => '_custom_price',
						'value'       => $product->get_custom_price(),
						'placeholder' => $product->get_price(),
						'type'        => 'price',
						'label'       => __( 'Afwijkend tarief', 'siw' ),
					]
				);
				?>
			</div>
		</div>

		<?php
	}

	/** Toont beschrijving van het project */
	public function show_description_tab() {
		global $product_object;
		$product = \siw_get_product( $product_object );
		if ( null == $product ) {
			return;
		}
		$description = $product->get_project_description();
		//TODO: verplaatsen naar WC_Product_Project

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
							<p><?php echo wp_kses_post( $description[ $topic]);?></p>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}

	/** Slaat gewijzigde meta-velden op */
	public function save_product_data( WC_Product_Project $product ) {

		//Als stockfoto gebruikt moet worden, verwijder dan de huidige foto TODO: Plato-foto echt verwijderen?/
		if ( isset( $_POST['_use_stockphoto'] ) && ! $product->use_stockfoto() ) {
			$product->set_image_id( null );
			$product->set_has_plato_image( false );
		}
		$product->set_custom_price( wc_clean( $_POST['_custom_price'] ) );
		$product->set_use_stockphoto( isset( $_POST['_use_stockphoto'] ) );
		$product->set_hidden( isset( $_POST['_hidden'] ) );
	}
}
