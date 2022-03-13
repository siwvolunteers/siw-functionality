<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

use SIW\Elements\Accordion;
use SIW\Elements\Form;
use SIW\Elements\Google_Maps;
use SIW\External\Exchange_Rates;
use SIW\Properties;
use SIW\WooCommerce\Product\WC_Product_Project;

/**
 * Tabs voor Groepsprojecten
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Product_Tabs {

	/** Formulier */
	const CONTACT_FORM_ID = 'enquiry_project';

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_and_rename_and_remove_product_tabs'] );
		add_filter( 'woocommerce_product_additional_information_heading', '__return_empty_string' );
	}

	/** Voegt tab met projectbeschrijving toe */
	public function add_and_rename_and_remove_product_tabs( array $tabs ) : array {
		global $post;
		$product = siw_get_product( $post );
		if ( null === $product ) {
			return $tabs;
		}

		$tabs['additional_information']['title'] = __( 'Eigenschappen', 'siw' );

		unset( $tabs['description']);

		$project_description = $product->get_project_description();
		if ( ! empty( $project_description ) ) {
			$tabs['project_description'] = [
				'title'       => __( 'Beschrijving', 'siw' ),
				'priority'    => 1,
				'callback'    => [ $this, 'show_project_description' ],
				'description' => $project_description,
			];
		}

		$latitude = $product->get_latitude();
		$longitude = $product->get_longitude();
	
		if ( 0 != $latitude && 0 != $longitude ) {
			$tabs['location'] = [
				'title'     => __( 'Projectlocatie', 'siw' ),
				'priority'  => 110,
				'callback'  => [ $this, 'show_project_map'],
				'latitude'  => $latitude,
				'longitude' => $longitude,
			];
		}

		$tabs['enquiry'] = [
			'title'    => __( 'Stel een vraag', 'siw' ),
			'priority' => 120,
			'callback' => [ $this, 'show_product_contact_form' ],
		];

		$tabs['costs'] = [
			'title'    => __( 'Kosten', 'siw' ),
			'priority' => 130,
			'callback' => [ $this, 'show_product_costs' ],
			'product'  => $product,
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
			if ( isset( $description[ $topic ] ) && ! empty( $description[ $topic ] ) ) {

				$panes[] = [
					'title'   => $title,
					'content' => wp_targeted_link_rel( links_add_target( make_clickable( $description[ $topic ] ) ) ),
				];
			}
		}
		Accordion::create()->add_items( $panes )->render();
	}

	/** Toont kaart met projectlocatie in tab */
	public function show_project_map( string $tab, array $args ) {
		Google_Maps::create()
			->add_marker( $args['latitude'], $args['longitude'], __( 'Projectlocatie', 'siw' ) )
			->render();
	}

	/** Toont contactformulier in tab */
	public function show_product_contact_form() {
		Form::create()->set_form_id( self::CONTACT_FORM_ID )->render();
	}

	/** Toont stappenplan in tab TODO: stappen uit instelling */
	public function show_product_costs( string $tab, array $args ) {

		/**@var WC_Product_Project */
		$product = $args['product'];
		
		printf(
			__( 'De inschrijfkosten voor dit project bedragen %s, exclusief %s studentenkorting.' ),
			siw_format_amount( (float) $product->get_price() ),
			siw_format_amount( Properties::STUDENT_DISCOUNT_AMOUNT )
		);

		$amount = $product->get_participation_fee();
		$currency_code = $product->get_participation_fee_currency();


		//Local fee niet tonen voor nederlandse projecten
		if ( ! empty( $currency_code ) && $amount > 0 && ! $product->is_dutch_project() ) {

			if ( get_woocommerce_currency() !== $currency_code ) {
				$exchange_rates = new Exchange_Rates();
				$amount_in_euro = $exchange_rates->convert_to_euro( $currency_code, $amount, 0 );
			}
			echo BR;
			printf(
				__( 'Let op: naast het inschrijfgeld betaal je ter plekke nog een lokale bijdrage van %s.' ),
				siw_format_amount( (float) $product->get_price(), 0, $currency_code )
			);
			if ( isset( $amount_in_euro ) ) {
				echo SPACE;
				printf(
					esc_html__( '(Ca. %s)', 'siw' ),
					siw_format_amount( (float) $amount_in_euro, 0 )
				);
			}
		}

	}
}
