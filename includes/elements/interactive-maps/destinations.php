<?php declare(strict_types=1);

namespace SIW\Elements\Interactive_Maps;

use SIW\Actions\Batch\Update_WooCommerce_Terms;
use SIW\Interfaces\Elements\Interactive_Map as Interactive_Map_Interface;

use SIW\i18n;
use SIW\Data\Country;
use SIW\Elements\List_Columns;
use SIW\Util\Links;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Class om een Mapplic kaart te genereren
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Destinations implements Interactive_Map_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'destinations';
	}

	/** {@inheritDoc} */
	public function get_file(): string {
		return 'world';
	}

	/** {@inheritDoc} */
	public function get_options(): array {
		return [
			'search'       => true,
			'searchfields' => [ 'title', 'about', 'description' ],
			'hidenofilter' => true,
		];
	}

	/** {@inheritDoc} */
	public function get_map_data(): array {
		return [
			'mapwidth'  => 1200,
			'mapheight' => 760,
		];
	}

	/** {@inheritDoc} */
	public function get_categories(): array {
		$continents = siw_get_continents();

		$categories = [];
		foreach ( $continents as $continent ) {
			$categories[] = [
				'id'    => $continent->get_slug(),
				'title' => $continent->get_name(),
				'color' => $continent->get_color(),
			];
		}
		return $categories;
	}

	/** {@inheritDoc} */
	public function get_locations(): array {
		$countries = siw_get_countries( Country::PROJECTS );

		$locations = [];
		foreach ( $countries as $country ) {
			$continent = $country->get_continent();
			$world_map_coordinates = $country->get_world_map_coordinates();

			$locations[] = [
				'id'          => $country->get_iso_code(),
				'title'       => $country->get_name(),
				'x'           => $world_map_coordinates->x ?? null,
				'y'           => $world_map_coordinates->y ?? null,
				'category'    => $continent->get_slug(),
				'fill'        => $continent->get_color(),
				'description' => $this->generate_country_description( $country ),
			];
		}
		return $locations;
	}

	/** Genereer beschrijving van aanbod per land */
	protected function generate_country_description( Country $country ): string {

		/* Groepsprojecten */
		if ( $country->has_workcamps() ) {
			$project_types[] = $this->generate_workcamps_description( $country );
		}

		/* Op maat*/
		if ( $country->has_tailor_made_projects() ) {
			$project_types[] = $this->generate_tailor_made_description( $country );
		}

		/* EVS */
		if ( $country->has_esc_projects() ) {
			$esc_page_link = i18n::get_translated_page_url( intval( siw_get_option( 'pages.explanation.esc' ) ) );
			$project_types[] = esc_html__( 'ESC', 'siw' ) . SPACE . Links::generate_link( $esc_page_link, __( 'Lees meer', 'siw' ) );
		}

		return esc_html__( 'In dit land bieden wij de volgende projecten aan:', 'siw' ) . List_Columns::create()->add_items( $project_types )->generate();
	}

	/** Genereert beschrijving voor groepsprojecten */
	protected function generate_workcamps_description( Country $country ): string {
		$country_term = get_term_by( 'slug', $country->get_slug(), Taxonomy_Attribute::COUNTRY()->value );

		if ( is_a( $country_term, \WP_Term::class ) ) {
			$workcamp_count = get_term_meta( $country_term->term_id, Update_WooCommerce_Terms::POST_COUNT_TERM_META, true );
		} else {
			$workcamp_count = 0;
		}

		if ( $workcamp_count > 0 ) {
			$url = get_term_link( $country->get_slug(), Taxonomy_Attribute::COUNTRY()->value );
			$text = __( 'Bekijk het aanbod', 'siw' );
		} else {
			$url = i18n::get_translated_page_url( intval( siw_get_option( 'pages.explanation.workcamps' ) ) );
			$text = __( 'Lees meer', 'siw' );
		}
		return esc_html__( 'Groepsprojecten', 'siw' ) . SPACE . Links::generate_link( $url, $text );
	}

	/** Genereert beschrijving voor Op Maat */
	public function generate_tailor_made_description( Country $country ): string {

		$tailor_made_page_link = i18n::get_translated_page_url( intval( siw_get_option( 'pages.explanation.tailor_made' ) ) );

		$tailor_made_pages = get_posts(
			[
				'posts_per_page' => -1,
				'meta_key'       => 'country', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $country->get_slug(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'post_type'      => 'siw_tm_country',
			]
		);
		if ( ! empty( $tailor_made_pages ) ) {
			$url = get_permalink( $tailor_made_pages[0] );
			$text = __( 'Bekijk het aanbod', 'siw' );
		} else {
			$url = $tailor_made_page_link;
			$text = __( 'Lees meer', 'siw' );
		}

		return esc_html__( 'Projecten Op Maat', 'siw' ) . SPACE . Links::generate_link( $url, $text );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @todo aanbod per land
	 */
	public function get_mobile_content() : string {
		$countries = siw_get_countries_list( Country::PROJECTS );
		return List_Columns::create()->add_items( array_values( $countries ) )->set_columns( 2 )->generate();
	}
}
