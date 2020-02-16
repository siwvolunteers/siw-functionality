<?php

namespace SIW\WooCommerce\Frontend;

use SIW\Formatting;
use SIW\Elements\Google_Maps;

/**
 * Tabs voor Groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @todo      reisadvies BuZa
 * @todo      stappenplan
 */
class Product_Tabs {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_product_tabs', [ $self, 'remove_reviews_tab'], PHP_INT_MAX );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_project_description_tab'] );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_project_location_map_tab'] );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_contact_form_tab'] );
		//add_filter( 'woocommerce_product_tabs', [ $self, 'add_steps_tab'] );
		//add_filter( 'woocommerce_product_tabs', [ $self, 'add_travel_advice_tab'] );
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
	 * Voegt tab met projectbeschrijving toe
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_project_description_tab( array $tabs ) {
		global $product;
		$description = $product->get_meta( 'description' );
		if ( empty( $description ) ) {
			return $tabs;
		}
		unset( $tabs['description']);
		$tabs['project_description'] = [
			'title'       => __( 'Beschrijving', 'siw' ),
			'priority'    => 1,
			'callback'    => [ $this, 'show_project_description' ],
			'description' => $description,
		];
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
	 * Voegt tab met stappenplan toe
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_steps_tab( array $tabs ) {
		$tabs['steps'] = [
			'title'    => __( 'Zo werkt het', 'siw' ),
			'priority' => 130,
			'callback' => [ $this, 'show_product_steps' ],
		];
		return $tabs;
	}

	/**
	 * Voegt tab met BuZa-reisadvies toe
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_travel_advice_tab( array $tabs ) {
		$tabs['travel_advice'] = [
			'title'    => __( 'Reisadvies', 'siw' ),
			'priority' => 140,
			'callback' => [ $this, 'show_travel_advice_steps' ],
		];
		return $tabs;
	}

	/**
	 * Toont projectbeschrijving o.b.v. gegevens uit Plato
	 *
	 * @param string $tab
	 * @param array $args
	 */
	public function show_project_description( string $tab, array $args ) {

		$description = $args['description'];

		$topics = [
			'description'           => __( 'Beschrijving', 'siw' ),
			'work'                  => __( 'Werk', 'siw' ),
			'accomodation_and_food' => __( 'Accommodatie en maaltijden', 'siw' ),
			'location_and_leisure'  => __( 'Locatie en vrije tijd', 'siw' ),
			'partner'               => __( 'Organisatie', 'siw' ),
			'requirements'          => __( 'Vereisten', 'siw' ),
			'notes'                 => __( 'Opmerkingen', 'siw' ),
		];

		foreach ( $topics as $topic => $title ) {
			if ( isset( $description[ $topic ] ) ) {

				$panes[] = [
					'title'   => $title,
					'content' => wp_targeted_link_rel( links_add_target( make_clickable( $description[ $topic ] ) ) ),
				];
			}
		}
		echo do_shortcode( Formatting::generate_accordion( $panes ) );
	}

	/**
	 * Toont kaart met projectlocatie in tab
	 * @param string $tab
	 * @param array $args
	 */
	public function show_project_map( string $tab, array $args ) {
		$map = new Google_Maps();
		$map->add_marker( $args['lat'], $args['lng'], __( 'Projectlocatie', 'siw' ) );
		$map->render();
	}

	/**
	 * Toont contactformulier in tab
	 */
	public function show_product_contact_form() {
		echo do_shortcode( '[caldera_form id="contact_project"]' );
	}

	/**
	 * Toont stappenplan in tab
	 * 
	 * @todo stappen uit databestand
	 */
	public function show_product_steps() {
		$columns = [
			[
				'width'   => 4,
				'content' => Formatting::generate_icon( 'siw-icon-file-signature', 2, 'circle' ) . '<br><h5>1. Aanmelding</h5>Heb je interesse in dit groepsproject? Meld je dan direct aan via de knop "Aanmelden".'
			],
			[
				'width'   => 4,
				'content' => Formatting::generate_icon( 'siw-icon-clipboard-check', 2, 'circle' ) . '<br><h5>2. Bevesting</h5>Binnen twee weken na betaling krijg je een bevestiging van plaatsing op het project.'
			],
			[
				'width'   => 4,
				'content' => Formatting::generate_icon( 'siw-icon-tasks', 2, 'circle' ) . '<br><h5>3. Voorbereiding</h5>Kom naar de voorbereidingsdag, zodat je goed voorbereid aan je avontuur kan beginnen.'
			],
		];
		echo '<div style="text-align:center">' .  do_shortcode( Formatting::generate_columns( $columns ) ) . '</div>'; //FIXME: geen inline styling
	}

	/**
	 * Toont reisadvies van BuZa
	 *
	 * @param string $tab
	 * @param array $args
	 */
	public function show_travel_advice_tab( string $tab, array $args ) {

	}
}
