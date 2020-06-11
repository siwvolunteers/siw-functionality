<?php

namespace SIW\Elements\Interactive_Maps;

use SIW\Elements;
use SIW\Elements\Interactive_Map;
use SIW\i18n;
use SIW\Formatting;
use SIW\Properties;
use SIW\Util\Links;
use WC_Product;

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Netherlands extends Interactive_Map {

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
		$provinces = [];
		foreach ( $projects as $project ) {
			$locations[] = [
				'id'            => sanitize_title( $project->get_sku() ),
				'title'         => $this->get_project_title( $project ),
				'image'         => $project->get_image_id() ? wp_get_attachment_image_src( $project->get_image_id(), 'medium' )[0] : null,
				'about'         => $project->get_sku(),
				'lat'           => $project->get_meta( 'latitude') ?? null,
				'lng'           => $project->get_meta( 'longitude') ?? null,
				'description'   => $this->get_project_properties( $project ) . $this->get_project_button( $project ),
				'pin'           => 'pin-classic pin-md',
				'category'      => 'nl',
				'fill'          => Properties::PRIMARY_COLOR,
			];
			$provinces[] = sprintf( '#nl-%s path', $project->get_meta( 'dutch_projects_province' ) );
		}
	
		/** Inline CSS */
		$provinces = array_unique( $provinces );
		$selectors = implode( ',', $provinces );
	
		$this->inline_css = [
			$selectors => [
				'fill' => Properties::SECONDARY_COLOR,
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
		$panes = [];
		foreach ( $projects as $project ) {
			$panes[] = [
				'title'       => $this->get_project_title( $project ),
				'content'     => $this->get_project_properties( $project ) . $this->get_project_description( $project ),
				'show_button' => i18n::is_default_language(),
				'button_url'  => $project->get_permalink(),
				'button_text' => __( 'Bekijk project', 'siw' ),
			];

		}
		return Elements::generate_accordion( $panes );
	}

	/**
	 * Haalt projecten op
	 * 
	 * @return array
	 */
	protected function get_projects() {
		$args = [
			'country'    => 'nederland',
			'return'     => 'objects',
			'limit'      => -1,
		];
		return wc_get_products( $args );
	}

	/**
	 * Genereert beschrijving van het project
	 *
	 * @param \WC_Product $project
	 * @param bool $project_code
	 * @return string
	 */
	protected function get_project_properties( \WC_Product $project ) {
		//Verzamelen gegevens
		$attributes = $project->get_attributes();
		$work_type_slugs = $attributes['pa_soort-werk']->get_slugs();
		
		$work_types = array_map(
		function( $work_type_slug ) {
			return siw_get_work_type( $work_type_slug )->get_name();
			},
			$work_type_slugs
		);
		
		$duration = Formatting::format_date_range( $project->get_attribute( 'startdatum' ), $project->get_attribute( 'einddatum' ) );

		//Opbouwen beschrijving
		$description[] = sprintf( __( 'Projectcode: %s', 'siw' ), $project->get_sku() );
		$description[] = sprintf( __( 'Data: %s', 'siw' ), $duration );
		$description[] = sprintf( __( 'Soort werk: %s', 'siw' ), implode( ', ', $work_types ) );
		
		//Locatie tonen indien bekend
		if ( $project->get_meta( 'dutch_projects_city' ) && $project->get_meta( 'dutch_projects_province' ) ) {
			$description[] = sprintf(
				__( 'Locatie: %s, provincie %s', 'siw' ),
				$project->get_meta('dutch_projects_city'),
				siw_get_dutch_province( $project->get_meta( 'dutch_projects_province' ) )
			);
		}
		return wpautop( implode( BR, $description ) );
	}

	/**
	 * Haalt projectbeschrijving op
	 *
	 * @param \WC_Product $project
	 * 
	 * @return string
	 */
	protected function get_project_description( \WC_Product $project ) {
		$language = i18n::get_current_language();
		if ( $project->get_meta( "dutch_projects_name_{$language}" ) ) {
			return wpautop( $project->get_meta( "dutch_projects_description_{$language}" ) );
		}
		return;
	}

	/**
	 * Haalt projecttitel op
	 *
	 * @param \WC_Product $project
	 * 
	 * @return string
	 */
	protected function get_project_title( \WC_Product $project ) {
		$language = i18n::get_current_language();
		return ! empty( $project->get_meta( "dutch_projects_name_{$language}" ) ) ? $project->get_meta( "dutch_projects_name_{$language}" ) : $project->get_attribute( 'Projectnaam' );
	}

	/**
	 * Haal knop naar Groepsproject op
	 *
	 * @param \WC_Product $project
	 *
	 * @return string
	 */
	protected function get_project_button( \WC_Product $project ) {
		if ( ! i18n::is_default_language() ) {
			return;
		}
		return Links::generate_button_link( $project->get_permalink(), __( 'Bekijk project', 'siw' ) );
	}
}