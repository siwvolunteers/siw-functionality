<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\i18n;

/**
 * Tabs voor Groepsprojecten
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.0
 */
class Product_Tabs {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_product_data_tabs', [ $self, 'add_tabs'] );
		add_filter( 'woocommerce_product_data_tabs', [ $self, 'hide_tabs'], PHP_INT_MAX );
		
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_description_tab'] );
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_update_tab'] );
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_dutch_projects_tab'] );

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
		$tabs['update'] = [
			'label'    => __( 'Update', 'siw' ),
			'target'   => 'update_product_data',
			'class'    => [],
			'priority' => 120,
		];
		if ( 'nederland' == $product_object->get_meta( 'country' ) ) {
			$tabs['dutch_projects'] = [
				'label'    => __( 'Nederlandse Projecten', 'siw' ),
				'target'   => 'dutch_projects_product_data',
				'class'    => [],
				'priority' => 120,
			];
		}
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


	/** Toont tab met met instellingen voor nederlandse projecten */
	public function show_dutch_projects_tab() {
		global $product_object;

		if ( 'nederland' !== $product_object->get_meta( 'country' ) ) {
			return;
		}

		$languages = i18n::get_active_languages();
		$provinces = [ '' => __( 'Selecteer een provincie', 'siw' ) ] + siw_get_dutch_provinces();

		?>
		<div id="dutch_projects_product_data" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php
				foreach ( $languages as $code => $language ) {
					woocommerce_wp_text_input(
						[
							'id'          => "dutch_projects_name_{$code}",
							'value'       => $product_object->get_meta( "dutch_projects_name_{$code}" ),
							'label'       => sprintf( __( 'Naam (%s)', 'siw' ), $language['translated_name'] ),
						]
					);
					woocommerce_wp_textarea_input(
						[
							'id'          => "dutch_projects_description_{$code}",
							'value'       => $product_object->get_meta( "dutch_projects_description_{$code}" ),
							'label'       => sprintf( __( 'Beschrijving (%s)', 'siw' ), $language['translated_name'] ),
						]
					);
				}
				woocommerce_wp_text_input(
					[
						'id'          => 'dutch_projects_city',
						'value'       => $product_object->get_meta( 'dutch_projects_city' ),
						'label'       => __( 'Plaats', 'siw' ),
					]
				);
				woocommerce_wp_select(
					[
						'id'         => 'dutch_projects_province',
						'value'      => $provinces[ $product_object->get_meta( 'dutch_projects_province' ) ] ?  $product_object->get_meta( 'dutch_projects_province' ) : '',
						'label'      => __( 'Provincie', 'siw' ),
						'options'    => $provinces,
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
			'dutch_projects_city'     => isset( $_POST['dutch_projects_city'] ) ? wc_clean( $_POST['dutch_projects_city'] ) : '',
			'dutch_projects_province' => isset( $_POST['dutch_projects_province'] ) ? wc_clean( $_POST['dutch_projects_province'] ) : '',
		];

		$languages = i18n::get_active_languages();
		foreach ( $languages as $code => $language ) {
			$meta_data["dutch_projects_name_{$code}"] = isset( $_POST["dutch_projects_name_{$code}"] ) ? wc_clean( $_POST["dutch_projects_name_{$code}"] ) : '';
			$meta_data["dutch_projects_description_{$code}"] = isset( $_POST["dutch_projects_description_{$code}"] ) ? wc_clean( $_POST["dutch_projects_description_{$code}"] ) : '';
		}
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
