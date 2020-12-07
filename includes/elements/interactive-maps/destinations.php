<?php declare(strict_types=1);

namespace SIW\Elements\Interactive_Maps;

use SIW\i18n;
use SIW\Data\Country;
use SIW\Elements;
use SIW\Elements\Interactive_Map;
use SIW\Util\Links;

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Destinations extends Interactive_Map {

	/**
	 * {@inheritDoc}
	 */
	protected string $id = 'destinations';

	/**
	 * {@inheritDoc}
	 */
	protected string $file = 'world';

	/**
	 * {@inheritDoc}
	 */
	protected array $data = [
		'mapwidth'  => 1200,
		'mapheight' => 760,
	];

	/**
	 * {@inheritDoc}
	 */
	protected array $options = [
		'search'       => true,
		'searchfields' => ['title', 'about', 'description'],
		'hidenofilter' => true,
	];

	/**
	 * {@inheritDoc}
	 */
	protected function get_categories() : array {
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

	/**
	 * {@inheritDoc}
	 */
	protected function get_locations() : array {
		$countries = siw_get_countries();
		
		$locations = [];
		foreach ( $countries as $country ) {
			if ( ! $country->is_allowed() ) {
				continue;
			}
			$continent = $country->get_continent();
			$world_map_data = $country->get_world_map_data();

			$locations[] = [
				'id'            => $world_map_data->code,
				'title'         => $country->get_name(),
				'x'             => $world_map_data->x,
				'y'             => $world_map_data->y,
				'category'      => $continent->get_slug(),
				'fill'          => $continent->get_color(),
				'description'   => $this->generate_country_description( $country ),
			];
		}
		return $locations;
	}

	/**
	 * Genereer beschrijving van aanbod per land
	 *
	 * @param Country $country
	 * @return string
	 */
	protected function generate_country_description( Country $country ) : string {

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

		return esc_html__( 'In dit land bieden wij de volgende projecten aan:', 'siw' ) . Elements::generate_list( $project_types );
	}

	/**
	 * Genereert beschrijving voor groepsprojecten
	 *
	 * @param Country $country
	 * @return string
	 */
	protected function generate_workcamps_description( Country $country ) : string {
		$country_term = get_term_by( 'slug', $country->get_slug(), 'pa_land' );
		
		if ( is_a( $country_term, 'WP_Term' ) ) {
			$workcamp_count = get_term_meta( $country_term->term_id, 'project_count', true );
		}
		else {
			$workcamp_count = 0;
		}

		if ( $workcamp_count > 0 ) {
			$url = get_term_link( $country->get_slug(), 'pa_land' );
			$text = __( 'Bekijk het aanbod', 'siw' );
		}
		else {
			$url = i18n::get_translated_page_url( intval( siw_get_option( 'pages.explanation.workcamps' ) ) );
			$text = __( 'Lees meer', 'siw' );
		}
		return esc_html__( 'Groepsprojecten', 'siw' ) . SPACE . Links::generate_link( $url, $text );
	}

	/**
	 * Genereert beschrijving voor Op Maat
	 *
	 * @param Country $country
	 * @return string
	 */
	public function generate_tailor_made_description( Country $country ) : string {

		$tailor_made_page_link = i18n::get_translated_page_url( intval( siw_get_option( 'pages.explanation.tailor_made' ) ) );

		$tailor_made_pages = get_posts( [
			'posts_per_page'   => -1,
			'meta_key'         => 'country',
			'meta_value'       => $country->get_slug(),
			'post_type'        => 'siw_tm_country',
		]);
		if ( ! empty( $tailor_made_pages) ) {
			$url = get_permalink( $tailor_made_pages[0] );
			$text = __( 'Bekijk het aanbod', 'siw' );
		}
		else {
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
	protected function get_mobile_content() : string {
		$countries = siw_get_countries( 'allowed', 'slug', 'array' );
		return Elements::generate_list(  array_values( $countries ), 2 );
	}
}
