<?php

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @package   SIW\Maps
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Formatting
 * @uses      SIW_i18n
 * */
class SIW_Element_Interactive_Map_Destinations extends SIW_Element_Interactive_Map {

	/**
	 * {@inheritDoc}
	 */
	protected $id = 'destinations';

	/**
	 * {@inheritDoc}
	 */
	protected $file = 'world';

	/**
	 * {@inheritDoc}
	 */
	protected $data = [
		'mapwidth'  => 1200,
		'mapheight' => 760,
	];

	/**
	 * {@inheritDoc}
	 */
	protected $options = [
		'search'       => true,
		'searchfields' => ['title', 'about', 'description'],
	];

	/**
	 * {@inheritDoc}
	 */
	protected function get_categories() {
		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_locations() {
		$countries = siw_get_countries();
		
		$locations = [];
		foreach ( $countries as $country ) {
			if ( true != $country->is_allowed() ) {
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
	 * @param SIW_Data_Country $country
	 * @return string
	 */
	protected function generate_country_description( SIW_Data_Country $country ) {

		/* Groepsprojecten */
		if ( true == $country->has_workcamps() ) {
			$project_types[] = $this->generate_workcamps_description( $country );
		}
	
		/* Op maat*/
		if ( true == $country->has_tailor_made_projects() ) {
			$project_types[] = $this->generate_tailor_made_description( $country );
		}
	
		/* EVS */
		if ( true == $country->has_esc_projects() ) {
			$esc_page_link = SIW_i18n::get_translated_page_url( siw_get_option( 'esc_explanation_page' ) );
			$project_types[] = esc_html__( 'ESC', 'siw' ) . SPACE . SIW_Formatting::generate_link( $esc_page_link, __( 'Lees meer', 'siw' ) );
		}

		return esc_html__( 'In dit land bieden wij de volgende projecten aan:', 'siw' ) . SIW_Formatting::generate_list( $project_types );
	}

	/**
	 * Genereert beschrijving voor groepsprojecten
	 *
	 * @param SIW_Data_Country $country
	 * @return string
	 */
	protected function generate_workcamps_description( SIW_Data_Country $country ) {
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
			$url = SIW_i18n::get_translated_page_url( siw_get_option( 'workcamps_explanation_page' ) );
			$text = __( 'Lees meer', 'siw' );
		}
		return esc_html__( 'Groepsprojecten', 'siw' ) . SPACE . SIW_Formatting::generate_link( $url, $text );
	}

	/**
	 * Genereert beschrijving voor Op Maat
	 *
	 * @param SIW_Data_Country $country
	 * @return string
	 */
	public function generate_tailor_made_description( SIW_Data_Country $country ) : string {

		$tailor_made_page_link = SIW_i18n::get_translated_page_url( siw_get_option( 'tailor_made_explanation_page' ) );

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

		return esc_html__( 'Projecten Op Maat', 'siw' ) . SPACE . SIW_Formatting::generate_link( $url, $text );
	}

	/**
	 * {@inheritDoc}
	 * 
	 * @todo lijst/tabel met aanbod per land
	 */
	protected function get_mobile_content() {
		return null;
	}
}
