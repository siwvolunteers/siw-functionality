<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

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
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_approval_tab'] );
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_update_tab'] );

		add_action( 'woocommerce_admin_process_product_object', [ $self, 'save_product_data'] );
	}

	/** Voegt extra product tabs toe */
	public function add_tabs( array $tabs ) : array {
		global $product_object;

		$tabs['description'] = [
			'label'    => __( 'Omschrijving', 'siw' ),
			'target'   => 'description_product_data',
			'class'    => [],
			'priority' => 1,
		];
		if ( ! empty( $product_object->get_meta('approval_result') ) ) {
			$tabs['approval'] = [
				'label'    => __( 'Beoordeling', 'siw' ),
				'target'   => 'approval_product_data',
				'class'    => [],
				'priority' => 110,
			];
		}
		$tabs['update'] = [
			'label'    => __( 'Update', 'siw' ),
			'target'   => 'update_product_data',
			'class'    => [],
			'priority' => 120,
		];
		return $tabs;
	}

	/** Verbergt overbodige product tabs */
	public function hide_tabs( array $tabs ) : array {
		$tabs['advanced']['class'] = ['show_if_simple'];
		$tabs['shipping']['class'] = ['show_if_simple'];
		$tabs['linked_product']['class'] = ['show_if_simple'];

		if ( ! current_user_can( 'manage_options' ) ) {
			$tabs['inventory']['class'] = ['show_if_simple'];
			$tabs['attribute']['class'] = ['show_if_simple'];
			$tabs['variations']['class'] = ['show_if_simple'];
		}
		return $tabs;
	}

	/** Toont tab met extra opties t.b.v. update */
	public function show_update_tab() {
		global $product_object;
		?>
		<div id="update_product_data" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php
				//Alleen tonen als het project een afbeelding uit Plato heeft of als de optie al aangevinkt is
				if ( $product_object->get_meta( 'has_plato_image', true ) || $product_object->get_meta( 'use_stockphoto' ) ) {
					woocommerce_wp_checkbox(
						[
							'id'          => 'use_stockphoto',
							'value'       => $product_object->get_meta( 'use_stockphoto' ),
							'cbvalue'     => '1',
							'label'       => __( 'Stockfoto gebruiken', 'siw' ),
							'description' => __( 'Bijvoorbeeld indien projectfoto niet geschikt is.', 'siw' ),
						]
					);
				}
				woocommerce_wp_checkbox(
					[
						'id'          => 'has_custom_tariff',
						'value'       => $product_object->get_meta( 'has_custom_tariff' ),
						'cbvalue'     => '1',
						'label'       => __( 'Heeft afwijkend tarief', 'siw' ),
						'description' => __( 'Tarief wordt niet automatisch bijgewerkt', 'siw' ),
					]
				);
				woocommerce_wp_checkbox(
					[
						'id'      => 'force_hide',
						'value'   => $product_object->get_meta( 'force_hide' ),
						'cbvalue' => '1',
						'label'   => __( 'Geforceerd verbergen', 'siw' ),
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

		$description = $product_object->get_meta( 'description' );

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
		<div id="description_product_data" class="panel woocommerce_options_panel wc-metaboxes-wrapper">
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

	/** Toont tab met beoordelingsresultaat */
	public function show_approval_tab() {
		global $product_object;

		if ( empty( $product_object->get_meta( 'approval_result' ) ) ) {
			return;
		}

		$approval_results = [
			'approved' => __( 'Goedgekeurd', 'siw' ),
			'rejected' => __( 'Afgewezen', 'siw' ),
		];
		?>
		<div id="approval_product_data" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php
				woocommerce_wp_text_input(
					[
						'id'          => 'approval_result',
						'value'       => $approval_results[$product_object->get_meta( 'approval_result' )],
						'label'       => __( 'Resultaat', 'siw' ),
						'options'     => [
							'approved' => __( 'Goedgekeurd', 'siw' ),
							'rejected' => __( 'Afgewezen', 'siw' ),
						],
						'custom_attributes' => [
							'readonly' => 'readonly',
							'disabled' => 'disabled',
						],
					]
				);
				woocommerce_wp_text_input(
					[
						'id'       => 'approval_user',
						'value'    => $product_object->get_meta( 'approval_user' ),
						'label'    => __( 'Gebruiker', 'siw' ),
						'custom_attributes' => [
							'readonly' => 'readonly',
							'disabled' => 'disabled',
						],
					]
				);
				woocommerce_wp_text_input(
					[
						'id'       => 'approval_date',
						'value'    => $product_object->get_meta( 'approval_date' ),
						'label'    => __( 'Datum', 'siw' ),
						'custom_attributes' => [
							'readonly' => 'readonly',
							'disabled' => 'disabled',
						],
					]
				);
				?>
			</div>
		</div>
		<?php
	}

	/** Slaat gewijzigde meta-velden op */
	public function save_product_data( \WC_Product $product ) {
		$meta_data = [
			'use_stockphoto'          => isset( $_POST['use_stockphoto'] ),
			'force_hide'              => isset( $_POST['force_hide'] ),
			'has_custom_tariff'       => isset( $_POST['has_custom_tariff'] ),
		];
		//Als stockfoto gebruikt moet worden, verwijder dan de huidige foto TODO: Plato-foto echt verwijderen?/
		if ( $meta_data['use_stockphoto'] && ! $product->get_meta( 'use_stockphoto' ) ) {
			$product->set_image_id( null );
			$product->update_meta_data( 'has_plato_image', false );
		}

		foreach ( $meta_data as $key => $value ) {
			$product->update_meta_data( $key, $value );
		}
	}

}
