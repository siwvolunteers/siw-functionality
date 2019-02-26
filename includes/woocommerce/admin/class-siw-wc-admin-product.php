<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * `TODO: beschrijving
 *
 * @package   SIW\WooCommerce
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Util
 * @uses      SIW_WC_Admin_Product_Columns
 * @uses      SIW_Admin_Notices
 */
class SIW_WC_Admin_Product {

	public static function init() {
		$self = new self();

		add_action( 'admin_enqueue_scripts', [ $self, 'add_inline_style'], PHP_INT_MAX );
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
		add_action( 'woocommerce_product_data_panels', [ $self, 'show_visibility_tab'] );
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
		require_once( __DIR__ . '/class-siw-wc-admin-product-columns.php' );
		new SIW_WC_Admin_Product_Columns( 'product', [] );
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
			$notices = new SIW_Admin_Notices;
			$count = count( $post_ids );
			$message = sprintf( _n( '%s project wordt opnieuw geïmporteerd.', '%s projecten worden opnieuw geïmporteerd.', $count, 'siw' ), $count );
			$notices->add_notice( 'info', $message , true);
		}
		return $redirect_to;
	}

	/**
	 * Toont optie om nog niet gepubliceerd project af of goed te keuren
	 *
	 * @param WP_Post $post
	 */
	public function show_approval_option( $post ) {
		if ( 'product' != $post->post_type || 'pending' != $post->post_status ) {
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
	 * @param WC_Product $product
	 */
	public function save_approval_result( $product ) {

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
		$tabs['visibility'] = [
			'label'    => __( 'Zichtbaarheid', 'siw' ),
			'target'   => 'visibility_product_data',
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
		return $tabs;
	}

	/**
	 * Toont tab met extra zichtbaarheids-opties
	 */
	public function show_visibility_tab() {
		global $product_object;
		?>
		<div id="visibility_product_data" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php
				woocommerce_wp_radio(
					[
						'id'          => 'manual_visibility',
						'value'       => $product_object->get_meta( 'manual_visibility' ),
						'label'       => __( 'Zichtbaarheid', 'siw' ),
						'options'     => [
							''     => __( 'Automatisch', 'siw' ),
							'hide' => __( 'Verbergen', 'siw' ),
						],
					]
				);
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
		?>
		<div id="description_product_data" class="panel woocommerce_options_panel">
			<div class="options_group">
			<?php
				$content = $product_object->get_description();
				$content = preg_replace( '/\[pane title="(.*?)"\]/', '<h4>$1</h4><p>', $content );
				$content = preg_replace( '/\[\/pane\]/', '</p><hr>', $content );
				$content = preg_replace( '/\[(.*?)\]/', '', $content );
				echo wp_kses_post( $content );
			?>
			</div>
		</div>
		<?php
	}

	/**
	 * Slaat gewijzigde meta-velden op
	 *
	 * @param WC_Product $product
	 */
	public function save_product_data( $product ) {
		$meta_data = [
			'manual_visibility' => isset( $_POST['manual_visibility'] ) ? wc_clean( $_POST['manual_visibility'] ) : null,
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
		remove_meta_box( 'woocommerce-product-images' , 'product', 'side', 'low' );

		if ( ! current_user_can( 'manage_options' ) ) {
			remove_meta_box( 'postimagediv', 'product', 'side' );
			remove_meta_box( 'tagsdiv-product_tag', 'product', 'normal' );
			remove_meta_box( 'product_catdiv', 'product', 'normal' );
		}
	}

	/**
	 * Voegt styling voor help tips toe
	 */
	public function add_inline_style() {
		$styles = [
			'#woocommerce-product-data ul.wc-tabs li.description_options a::before' => [
				'content' => '"\f123"'
			],
			'#woocommerce-product-data ul.wc-tabs li.visibility_options a::before' => [
				'content' => '"\f177"'
			],
			'#woocommerce-product-data ul.wc-tabs li.approval_options a::before' => [
				'content' => '"\f529"'
			],
		];
		$data = SIW_Util::generate_css( $styles );

		wp_add_inline_style( 'woocommerce_admin_styles', $data );
	}
}
