<?php

namespace SIW\WooCommerce\Admin;

use SIW\CSS;
use SIW\Admin\Notices as Admin_Notices;
use SIW\WooCommerce\Import\Product as Import_Product;

/**
 * Aanpassingen aan admin-scherm voor producten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Product {

	public static function init() {
		$self = new self();

		add_action( 'add_meta_boxes', [ $self, 'remove_meta_boxes'], PHP_INT_MAX );
		add_filter( 'woocommerce_duplicate_product_capability', '__return_empty_string' );

		add_action( 'init', [ $self, 'remove_editor'], PHP_INT_MAX ); 

		add_filter( 'manage_edit-product_columns', [ $self, 'remove_admin_columns'] );
		add_action( 'admin_init', [ $self, 'add_admin_columns'], 20 );
		add_filter( 'bulk_actions-edit-product', [ $self, 'add_bulk_actions'] );
		add_filter( 'handle_bulk_actions-edit-product', [ $self, 'handle_bulk_actions'], 10, 3 );
		add_action( 'post_submitbox_start', [ $self, 'show_approval_option'] );

		add_filter( 'woocommerce_product_data_tabs', [ $self, 'add_tabs'] );
		add_filter( 'woocommerce_product_data_tabs', [ $self, 'hide_tabs'] );
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_import_tab'] );
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_approval_tab'] );
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_description_tab'] );

		add_action( 'woocommerce_admin_process_product_object', [ $self, 'save_product_data'] );
		add_action( 'woocommerce_admin_process_product_object', [ $self, 'save_approval_result'] );
	}

	/**
	 * Verwijdert de texteditor
	 */
	public function remove_editor() {
		remove_post_type_support( 'product', 'editor' );
	}

	/**
	 * Verwijdert overbodige admin columns
	 *
	 * @param array $columns
	 * @return array
	 */
	public function remove_admin_columns( $columns ) {
		unset( $columns['date'] );
		unset( $columns['product-tag'] );
		unset( $columns['price'] );
		return $columns;
	}

	/**
	 * Voegt extra admin columns toe
	 *
	 * @return void
	 */
	public function add_admin_columns() {
		if ( ! class_exists( '\MB_Admin_Columns_Post' ) ) {
			return;
		}
		new Product_Columns( 'product', [] );
	}

	/**
	 * Voegt bulk acties toe
	 * 
	 * - Opnieuw importeren
	 *
	 * @param array $bulk_actions
	 * @return array
	 */
	public function add_bulk_actions( $bulk_actions ) {
		$bulk_actions['import_again'] = __( 'Opnieuw importeren', 'siw' );
		return $bulk_actions;
	}

	/**
	 * Verwerkt bulkacties
	 *
	 * @param string $redirect_to
	 * @param string $action
	 * @param array $post_ids
	 * @return string
	 */
	public function handle_bulk_actions( $redirect_to, $action, $post_ids ) {
		if ( 'import_again' === $action ) {
			foreach ( $post_ids as $post_id ) {
				update_post_meta( $post_id, 'import_again', true );
			}
			$notices = new Admin_Notices;
			$count = count( $post_ids );
			$message = sprintf( _n( '%s project wordt opnieuw geïmporteerd.', '%s projecten worden opnieuw geïmporteerd.', $count, 'siw' ), $count );
			$notices->add_notice( 'info', $message , true);
		}
		return $redirect_to;
	}

	/**
	 * Toont optie om nog niet gepubliceerd project af of goed te keuren
	 *
	 * @param \WP_Post $post
	 */
	public function show_approval_option( $post ) {
		if ( 'product' != $post->post_type || Import_Product::REVIEW_STATUS != $post->post_status ) {
			return;
		}
		$product = wc_get_product( $post->ID );
		$approval_result = $product->get_meta( 'approval_result' );
		woocommerce_wp_radio(
			[
				'id'          => 'approval_result',
				'value'       => ! empty( $approval_result ) ? $approval_result : 'approved',
				'label'       => __( 'Beoordeling project', 'siw' ),
				'options'     => [
					'approved' => __( 'Goedkeuren', 'siw' ),
					'rejected' => __( 'Afkeuren', 'siw' ),
				],
			]
		);
	}
	
	/**
	 * Slaat het resultaat van de beoordeling op
	 *
	 * @param \WC_Product $product
	 */
	public function save_approval_result( \WC_Product $product ) {

		if ( ! isset( $_POST['approval_result'] ) ) {
			return;
		}

		$meta_data = [
			'approval_result' => wc_clean( $_POST['approval_result'] ),
			'approval_user'   => wp_get_current_user()->display_name,
			'approval_date'   => current_time( 'Y-m-d' ),
		];

		foreach ( $meta_data as $key => $value ) {
			$product->update_meta_data( $key, $value );
		}

		if ( 'rejected' == $meta_data['approval_result'] ) {
			$product->set_catalog_visibility( 'hidden' );
		}
	}

	/**
	 * Voegt extra product tabs toe
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_tabs( $tabs ) {
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
		$tabs['import'] = [
			'label'    => __( 'Import', 'siw' ),
			'target'   => 'import_product_data',
			'class'    => [],
			'priority' => 120,
		];
		return $tabs;
	}

	/**
	 * Verbergt overbodige product tabs
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function hide_tabs( $tabs ) {
		$tabs['advanced']['class'] = [ 'show_if_simple'];
		$tabs['shipping']['class'] = [ 'show_if_simple'];
		$tabs['linked_product']['class'] = [ 'show_if_simple'];
		if ( ! current_user_can( 'manage_options' ) ) {
			$tabs['inventory']['class'] = [ 'show_if_simple'];
			$tabs['attribute']['class'] = [ 'show_if_simple'];
			$tabs['variations']['class'] = [ 'show_if_simple'];

		}

		return $tabs;
	}

	/**
	 * Toont tab met extra zichtbaarheids-opties
	 */
	public function show_import_tab() {
		global $product_object;
		?>
		<div id="import_product_data" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php
				woocommerce_wp_checkbox(
					[
						'id'      => 'import_again',
						'value'   => $product_object->get_meta( 'import_again' ),
						'cbvalue' => '1',
						'label'   => __( 'Opnieuw importeren', 'siw' ),
					]
				);
				?>
			</div>
		</div>

		<?php
	}

	/**
	 * Toont tab met beoordelingsresultaat
	 */
	public function show_approval_tab() {
		global $product_object;

		if ( empty( $product_object->get_meta('approval_result') ) ) {
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
						'id'  => 'approval_user',
						'value'    => $product_object->get_meta('approval_user'),
						'label'   => __('Gebruiker', 'siw'),
						'custom_attributes' => [
							'readonly' => 'readonly',
							'disabled' => 'disabled',
						],
					]
				);
				woocommerce_wp_text_input(
					[
						'id'  => 'approval_date',
						'value'    => $product_object->get_meta('approval_date'),
						'label'   => __( 'Datum', 'siw' ),
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

	/**
	 * Toont beschrijving van het project
	 */
	public function show_description_tab() {
		global $product_object;

		$description = $product_object->get_meta('description');

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
		<div id="description_product_data" class="panel woocommerce_options_panel">
			<div class="options_group">
			<?php
				foreach ( $topics as $topic => $title ) {
					if ( isset( $description[ $topic ] ) ) {
						printf('<h4>%s</h4><p>%s<p>', esc_html( $title ), wp_kses_post( $description[ $topic]) );
					}
				}
				
				//Legacy code, kan uiteindelijk weg
				if ( empty( $description ) ) {
					$content = $product_object->get_description();
					$content = preg_replace( '/\[pane title="(.*?)"\]/', '<h4>$1</h4><p>', $content );
					$content = preg_replace( '/\[\/pane\]/', '</p><hr>', $content );
					$content = preg_replace( '/\[(.*?)\]/', '', $content );
					echo wp_kses_post( $content );
				}

			?>
			</div>
		</div>
		<?php
	}

	/**
	 * Slaat gewijzigde meta-velden op
	 *
	 * @param \WC_Product $product
	 */
	public function save_product_data( \WC_Product $product ) {
		$meta_data = [
			'import_again'      => isset( $_POST['import_again'] ),
		];

		foreach ( $meta_data as $key => $value ) {
			$product->update_meta_data( $key, $value );
		}
	}

	/**
	 * Verwijdert metaboxes
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'slugdiv', 'product', 'normal' );
		remove_meta_box( 'postcustom' , 'product' , 'normal' );
		if ( ! current_user_can( 'manage_options' ) ) {
			remove_meta_box( 'postimagediv', 'product', 'side' );
			remove_meta_box( 'woocommerce-product-images' , 'product', 'side' );
			remove_meta_box( 'tagsdiv-product_tag', 'product', 'normal' );
			remove_meta_box( 'product_catdiv', 'product', 'normal' );
		}
	}
}
