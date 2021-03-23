<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

use SIW\Elements\Accordion;
use SIW\Elements\Features;
use SIW\Elements\Google_Maps;

/**
 * Tabs voor Groepsprojecten
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Product_Tabs {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_product_tabs', [ $self, 'remove_reviews_tab'], PHP_INT_MAX );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_project_description_tab'] );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_project_location_map_tab'] );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_contact_form_tab'] );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_steps_tab'] );
	}

	/** Verwijdert reviews-tab */
	public function remove_reviews_tab( array $tabs ) : array {
		unset( $tabs['reviews'] );
		return $tabs;
	}

	/** Voegt tab met projectbeschrijving toe */
	public function add_project_description_tab( array $tabs ) : array {
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
	
	/** Voegt tab met projectlocatie toe */
	public function add_project_location_map_tab( array $tabs ) : array {
		global $product;
		$lat = (float) $product->get_meta( 'latitude' );
		$lng = (float) $product->get_meta( 'longitude' );
	
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

	/** Voegt tab met contactformulier toe */
	public function add_contact_form_tab( array $tabs ) : array {
		$tabs['enquiry'] = [
			'title'    => __( 'Stel een vraag', 'siw' ),
			'priority' => 120,
			'callback' => [ $this, 'show_product_contact_form' ],
		];
		return $tabs;
	}

	/** Voegt tab met stappenplan toe */
	public function add_steps_tab( array $tabs ) : array {
		$tabs['steps'] = [
			'title'    => __( 'Zo werkt het', 'siw' ),
			'priority' => 130,
			'callback' => [ $this, 'show_product_steps' ],
		];
		return $tabs;
	}

	/** Toont projectbeschrijving o.b.v. gegevens uit Plato */
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
		Accordion::create( $panes )->render();
	}

	/** Toont kaart met projectlocatie in tab */
	public function show_project_map( string $tab, array $args ) {
		Google_Maps::create()
			->add_marker( $args['lat'], $args['lng'], __( 'Projectlocatie', 'siw' ) )
			->render();
	}

	/** Toont contactformulier in tab */
	public function show_product_contact_form() {
		echo do_shortcode( '[caldera_form id="contact_project"]' );
	}

	/** Toont stappenplan in tab TODO: stappen uit instelling */
	public function show_product_steps() {
		Features::create()
			->set_columns( 3 ) //TODO: stappen tellen
			->add_items( [
				[
					'icon'    => 'siw-icon-file-signature',
					'title'   => '1. Aanmelding',
					'content' => 'Heb je interesse in dit groepsproject? Meld je dan direct aan via de knop "Aanmelden".',
				],
				[
					'icon'    => 'siw-icon-clipboard-check',
					'title'   => '2. Bevesting',
					'content' => 'Binnen twee weken na betaling krijg je een bevestiging van plaatsing op het project.',
				],
				[
					'icon'    => 'siw-icon-tasks',
					'title'   => '3. Voorbereiding',
					'content' => 'Kom naar de voorbereidingsdag, zodat je goed voorbereid aan je avontuur kan beginnen.',
				],
			])->render();
	}

}
