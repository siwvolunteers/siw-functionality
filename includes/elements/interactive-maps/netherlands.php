<?php declare(strict_types=1);

namespace SIW\Elements\Interactive_Maps;

use SIW\Interfaces\Elements\Interactive_Map as Interactive_Map_Interface;

use SIW\Elements\Accordion;
use SIW\i18n;
use SIW\Properties;
use SIW\Util\Links;

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Netherlands implements Interactive_Map_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'nl';
	}

	/** {@inheritDoc} */
	public function get_file(): string {
		return 'netherlands';
	}

	/** {@inheritDoc} */
	public function get_options(): array {
		return [
			'alphabetic'   => false,
			'search'       => true,
			'searchfields' => ['title', 'about', 'description'],
		];
	}

	/** {@inheritDoc} */
	public function get_map_data(): array {
		return [
			'mapwidth'  => 600,
			'mapheight' => 600,
			'bottomLat' => '50.67500192979909',
			'leftLng'   => '2.8680356443589807',
			'topLat'    => '53.62609096857893',
			'rightLng'  => '7.679884929662812',
		];
	}

	/** {@inheritDoc} */
	public function get_categories() : array {
		return [];
	}

	/** {@inheritDoc} */
	public function get_locations() : array {
		$projects = $this->get_projects();
		$locations = [];
		$provinces = [];
		foreach ( $projects as $project ) {
			$locations[] = [
				'id'            => sanitize_title( $project->get_sku() ),
				'title'         => $this->get_project_title( $project ),
				'image'         => $project->get_image_id() ? wp_get_attachment_image_src( $project->get_image_id(), 'medium' )[0] : null,
				'about'         => $project->get_sku(),
				'lat'           => $project->get_meta( 'latitude' ) ?? null,
				'lng'           => $project->get_meta( 'longitude' ) ?? null,
				'description'   => $this->get_project_properties( $project ) . $this->get_project_button( $project ),
				'pin'           => 'pin-classic pin-md',
				'category'      => 'nl',
				'fill'          => Properties::PRIMARY_COLOR,
			];
			$provinces[] = null; // TODO: provincie uit Google Maps halen
		}
	
		//Provincies inkleuren
		$provinces = array_unique( $provinces );
		foreach ( $provinces as $province ) {
			$locations[] = [
				'id'     => "nl-{$province}",
				'fill'   => Properties::SECONDARY_COLOR,
				'action' => 'disabled',
				'hide'   => true,
			];
		}
		
		return $locations;
	}

	/** {@inheritDoc} */
	public function get_mobile_content() : string {
		
		$projects = $this->get_projects();
		if ( empty( $projects ) ) {
			return '';
		}
		$panes = [];
		foreach ( $projects as $project ) {
			$panes[] = [
				'title'       => $this->get_project_title( $project ),
				'content'     => $this->get_project_properties( $project ),
				'show_button' => i18n::is_default_language(),
				'button_url'  => $project->get_permalink(),
				'button_text' => __( 'Bekijk project', 'siw' ),
			];

		}
		return Accordion::create()->add_items( $panes )->generate();
	}

	/** Haalt projecten op */
	protected function get_projects() : array {
		$args = [
			'country'    => 'nederland',
			'return'     => 'objects',
			'limit'      => -1,
		];
		return wc_get_products( $args );
	}

	/** Genereert beschrijving van het project */
	protected function get_project_properties( \WC_Product $project ) : string {
		//Verzamelen gegevens
		$attributes = $project->get_attributes();
		$work_type_slugs = $attributes['pa_soort-werk']->get_slugs();
		
		$work_types = array_map(
			fn( string $work_type_slug ) : string => siw_get_work_type( $work_type_slug )->get_name(),
			$work_type_slugs
		);
	
		$duration = siw_format_date_range( $project->get_attribute( 'startdatum' ), $project->get_attribute( 'einddatum' ) );

		//Opbouwen beschrijving
		$description[] = sprintf( __( 'Projectcode: %s', 'siw' ), $project->get_sku() );
		$description[] = sprintf( __( 'Data: %s', 'siw' ), $duration );
		$description[] = sprintf( __( 'Soort werk: %s', 'siw' ), implode( ', ', $work_types ) );
		
		//TODO: Locatie (Locatie: %s, provincie %s) tonen indien bekend, afleiden van coördinaten m.b.v Google Maps API
		return wpautop( implode( BR, $description ) );
	}

	/** Haalt projecttitel op */
	protected function get_project_title( \WC_Product $project ) : string {
		return $project->get_attribute( 'Projectnaam' );
	}

	/** Haal knop naar Groepsproject op */
	protected function get_project_button( \WC_Product $project ) : ?string {
		if ( ! i18n::is_default_language() ) {
			return null;
		}
		return Links::generate_button_link( $project->get_permalink(), __( 'Bekijk project', 'siw' ) );
	}
}
