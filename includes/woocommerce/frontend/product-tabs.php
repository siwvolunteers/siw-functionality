<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

use SIW\Config;
use SIW\Elements\Form;
use SIW\Elements\Google_Maps;
use SIW\External\Exchange_Rates;
use SIW\Forms\Forms\Enquiry_Project;
use SIW\WooCommerce\Product\WC_Product_Project;

/**
 * Tabs voor Groepsprojecten
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Product_Tabs {

	/** Formulier */
	const CONTACT_FORM_ID = 'enquiry_project';

	const LOCATION_TAB = 'location_and_leisure';

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_and_rename_and_remove_product_tabs' ] );
		add_filter( 'woocommerce_product_additional_information_heading', '__return_empty_string' );
	}

	/** Voegt tab met projectbeschrijving toe */
	public function add_and_rename_and_remove_product_tabs( array $tabs ): array {
		global $post;
		$product = siw_get_product( $post );
		if ( null === $product ) {
			return $tabs;
		}

		$tabs['additional_information']['title'] = __( 'Eigenschappen', 'siw' );
		unset( $tabs['description'] );

		$description = $product->get_project_description();
		$topics = [
			'description'           => __( 'Beschrijving', 'siw' ),
			'work'                  => __( 'Werk', 'siw' ),
			'accomodation_and_food' => __( 'Accommodatie en maaltijden', 'siw' ),
			'location_and_leisure'  => __( 'Locatie en vrije tijd', 'siw' ),
			'partner'               => __( 'Organisatie', 'siw' ),
			'requirements'          => __( 'Vereisten', 'siw' ),
			'notes'                 => __( 'Opmerkingen', 'siw' ),
		];
		$priority = 1;
		foreach ( $topics as $topic => $title ) {
			if ( isset( $description[ $topic ] ) && ! empty( $description[ $topic ] ) ) {

				$tabs[ $topic ] = [
					'title'    => $title,
					'priority' => $priority,
					'callback' => [ $this, 'show_project_description' ],
					'content'  => $description[ $topic ],
					'product'  => $product,
				];

				$priority++;
			} elseif ( self::LOCATION_TAB === $topic && null !== $product->get_latitude() && null !== $product->get_longitude() ) {
				$tabs[ $topic ] = [
					'title'     => __( 'Locatie', 'siw' ),
					'priority'  => $priority,
					'callback'  => [ $this, 'show_project_map' ],
					'latitude'  => $product->get_latitude(),
					'longitude' => $product->get_longitude(),
				];
				$priority++;
			}
		}

		$tabs['costs'] = [
			'title'    => __( 'Kosten', 'siw' ),
			'priority' => 110,
			'callback' => [ $this, 'show_product_costs' ],
			'product'  => $product,
		];

		$tabs['enquiry'] = [
			'title'    => __( 'Stel een vraag', 'siw' ),
			'priority' => 120,
			'callback' => [ $this, 'show_product_contact_form' ],
		];

		return $tabs;
	}

	/** Toont projectbeschrijving o.b.v. gegevens uit Plato */
	public function show_project_description( string $tab, array $args ) {
		echo wp_kses_post( ( wp_targeted_link_rel( links_add_target( make_clickable( wpautop( $args['content'] ) ) ) ) ) );

		// TODO: toon disclaimer dat informatie van partner komt.

		/**@var WC_Product_Project */
		$product = $args['product'];
		if ( self::LOCATION_TAB === $tab && $product->get_latitude() && null !== $product->get_longitude() ) {
			Google_Maps::create()
			->add_marker( $product->get_latitude(), $product->get_longitude(), __( 'Projectlocatie', 'siw' ) )
			->render();
		}
	}

	/** Toont kaart met projectlocatie in tab */
	public function show_project_map( string $tab, array $args ) {
		Google_Maps::create()
			->add_marker( $args['latitude'], $args['longitude'], __( 'Projectlocatie', 'siw' ) )
			->render();
	}

	/** Toont contactformulier in tab */
	public function show_product_contact_form() {
		Form::create()->set_form_id( Enquiry_Project::FORM_ID )->render();
	}

	/** Toont overzicht van kosten voor het project */
	public function show_product_costs( string $tab, array $args ) {

		/**@var WC_Product_Project */
		$product = $args['product'];

		printf(
			// translators: %1$s is het inschrijfgeld %2$s is het bedrag studentenkorting
			esc_html__( 'Het inschrijfgeld voor dit project bedraagt %1$s, exclusief %2$s studentenkorting.', 'siw' ),
			esc_html( siw_format_amount( (float) $product->get_price() ) ),
			esc_html( siw_format_amount( Config::get_student_discount_amount() ) )
		);

		// Local fee niet tonen voor nederlandse projecten
		if ( $product->has_participation_fee() && ! $product->is_dutch_project() ) {

			$currency_code = $product->get_participation_fee_currency();

			if ( get_woocommerce_currency() !== $currency_code ) {
				$exchange_rates = new Exchange_Rates();
				$amount_in_euro = $exchange_rates->convert_to_euro( $currency_code, $product->get_participation_fee(), 0 );
			}
			echo BR; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf(
				// translators: %s is een bedrag
				esc_html__( 'Let op: naast het inschrijfgeld betaal je ter plekke nog een lokale bijdrage van %s.', 'siw' ),
				esc_html( siw_format_amount( $product->get_participation_fee(), 0, $currency_code ) )
			);
			if ( isset( $amount_in_euro ) ) {
				echo SPACE; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				printf(
					// translators: %s is het bedrag lokale bijdrage
					esc_html__( '(Ca. %s)', 'siw' ),
					esc_html( siw_format_amount( (float) $amount_in_euro, 0 ) )
				);
			}
		}

	}
}
