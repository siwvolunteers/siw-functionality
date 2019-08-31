<?php

/**
 * Tabs voor Groepsprojecten
 *
 * @package   SIW\WooCommerce
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Element_Google_Map
 * @todo      reisadvies BuZa
 */

class SIW_WC_Product_Tabs {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_product_tabs', [ $self, 'remove_reviews_tab'], PHP_INT_MAX );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_project_location_map_tab'] );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_contact_form_tab'] );
	}

	/**
	 * Verwijdert reviews-tab
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function remove_reviews_tab( array $tabs ) {
		unset( $tabs['reviews'] );
		return $tabs;
	}

	/**
	 * Voegt tab met projectlocatie toe
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_project_location_map_tab( array $tabs ) {
		global $product;
		$lat = $product->get_meta( 'latitude' );
		$lng = $product->get_meta( 'longitude' );
	
		if ( 0 != $lat && 0 != $lng ) {
			$tabs['location'] = [
				'title'     => __( 'Projectlocatie', 'siw' ),
				'priority'  => 110,
				'callback'  => [ $this, 'show_project_map'],
				'lat'       => $lat,
				'lng'       => $lng,
			];
		}
		return $tabs;
	}

	/**
	 * Voegt tab met contactformulier toe
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_contact_form_tab( array $tabs ) {
		$tabs['enquiry'] = [
			'title'    => __( 'Stel een vraag', 'siw' ),
			'priority' => 120,
			'callback' => [ $this, 'show_product_contact_form' ],
		];
		return $tabs;
	}

	/**
	 * Toont kaart met projectlocatie in tab
	 * @param string $tab
	 * @param array $args
	 * 
	 * @todo projectlocatie uit meta halen
	 */
	public function show_project_map( string $tab, array $args ) {
		$map = new SIW_Element_Google_Maps();
		$map->add_marker( $args['lat'], $args['lng'], __( 'Projectlocatie', 'siw' ) );
		$map->render();
	}

	/**
	 * Toont contactformulier in tab
	 */
	public function show_product_contact_form() {
		echo do_shortcode( '[caldera_form id="contact_project"]' );
	}
}
