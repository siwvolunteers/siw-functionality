<?php

namespace SIW\Elements;

use SIW\i18n;
use SIW\Formatting;
use SIW\Properties;

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Interactive_Map_Netherlands extends Interactive_Map {

	/**
	 * {@inheritDoc}
	 */
	protected $id = 'nl';

	/**
	 * {@inheritDoc}
	 */
	protected $file = 'netherlands';
	
	/**
	 * {@inheritDoc}
	 */
	protected $data = [
		'mapwidth'  => 600,
		'mapheight' => 600,
		'bottomLat' => '50.67500192979909',
		'leftLng'   => '2.8680356443589807',
		'topLat'    => '53.62609096857893',
		'rightLng'  => '7.679884929662812',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $options = [
		'alphabetic'   => false,
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
		$projects = $this->get_projects();
		$locations = [];
		foreach ( $projects as $project ) {
			$locations[] = [
				'id'            => sanitize_title( $project['code'] ),
				'title'         => $this->generate_project_title( $project ),
				'image'         => isset( $project['image'] ) ? wp_get_attachment_image_src( $project['image'][0], 'medium' )[0] : null,
				'about'         => $project['code'],
				'lat'           => $project['latitude'] ?? null,
				'lng'           => $project['longitude'] ?? null,
				'description'   => $this->generate_project_description( $project, true ),
				'pin'           => 'circular pin-md pin-label',
				'category'      => 'nl',
				'fill'          => Properties::SECONDARY_COLOR,
			];
			$provinces[] = sprintf( '#nl-%s path', $project['province'] );
		}
	
		/** Inline CSS */
		$provinces = array_unique( $provinces );
		$selectors = implode( ',', $provinces );
	
		$this->inline_css = [
			$selectors => [
				'fill' => Properties::PRIMARY_COLOR,
			],
		];
		
		return $locations;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_mobile_content() {
		
		$projects = $this->get_projects();
		if ( empty( $projects ) ) {
			return;
		}
		$mobile_content = '';
		foreach ( $projects as $project ) {
			$description = sprintf( '<b>%s</b>', $this->generate_project_title( $project, true ) ) . BR;
			$description .= $this->generate_project_description( $project );
			$mobile_content .= wpautop( $description );
		}
		return $mobile_content;
	}

	/**
	 * Haalt projecten op
	 * 
	 * @return array
	 */
	protected function get_projects() {
		$projects = siw_get_option('dutch_projects');
		return $projects;
	}

	/**
	 * Genereert beschrijving van het project
	 *
	 * @param array $project
	 * @param bool $project_code
	 * @return string
	 */
	protected function generate_project_description( array $project, bool $show_project_code = false ) {
		//Verzamelen gegevens
		$work_type = siw_get_work_type( $project['work_type'] );
		$provinces = siw_get_dutch_provinces();
		$province_name = $provinces[ $project['province'] ] ?? '';
		$duration = Formatting::format_date_range( $project['start_date'], $project['end_date'] );

		//Opbouwen beschrijving
		$description = [];
		if ( $show_project_code ) {
			$description[] = sprintf( __( 'Projectcode: %s', 'siw' ), $project['code'] );
		}
		$description[] = sprintf( __( 'Data: %s', 'siw' ), $duration );
		$description[] = sprintf( __( 'Deelnemers: %s', 'siw' ), $project['participants'] );
		$description[] = sprintf( __( 'Soort werk: %s', 'siw' ), $work_type ? $work_type->get_name() : '' );

		if ( isset( $project['local_fee'] ) ) {
			$description[] = sprintf( __( 'Lokale bijdrage: %s', 'siw' ), Formatting::format_amount( $project['local_fee'] ) );
		}
		$description[] = sprintf( __( 'Locatie: %s, provincie %s', 'siw' ), $project['city'], $province_name );

		return Formatting::array_to_text( $description, BR );
	}

	/**
	 * Genereert projecttitel
	 *
	 * @param array $project
	 * @param bool $prefix_with_code
	 * @return string
	 */
	protected function generate_project_title( array $project, bool $prefix_with_code = false ) {
		$language = i18n::get_current_language();
		$title = $project["name_{$language}"];
		if ( $prefix_with_code ) {
			$title = "{$project['code']} - {$title}";
		}
		return $title;
	}
}
