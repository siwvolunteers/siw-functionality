<?php declare(strict_types=1);

namespace SIW\Elements\Interactive_Maps;

use SIW\Interfaces\Elements\Interactive_Map as Interactive_Map_Interface;

use SIW\Data\Sustainable_Development_Goal;
use SIW\Data\Work_Type;
use SIW\Elements\Accordion;
use SIW\i18n;
use SIW\Properties;
use SIW\Util\Links;
use SIW\WooCommerce\WC_Product_Project;

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
	public function get_categories(): array {
		return [];
	}

	/** {@inheritDoc} */
	public function get_locations(): array {
		$projects = $this->get_projects();
		$locations = [];
		$provinces = [];
		foreach ( $projects as $project ) {
			$locations[] = [
				'id'            => sanitize_title( $project->get_sku() ),
				'title'         => $project->get_name(),
				'image'         => $project->get_image_id() ? wp_get_attachment_image_src( $project->get_image_id(), 'medium' )[0] : null,
				'about'         => $project->get_sku(),
				'lat'           => $project->get_latitude() ?? null,
				'lng'           => $project->get_longitude() ?? null,
				'description'   => $this->get_project_properties( $project ) . $this->get_project_button( $project ),
				'pin'           => 'pin-classic pin-md',
				'category'      => 'nl',
				'fill'          => Properties::PRIMARY_COLOR,
			];
			$provinces[] = $project->get_meta( 'dutch_projects_province' );
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
	public function get_mobile_content(): ?string {
		
		$projects = $this->get_projects();
		if ( empty( $projects ) ) {
			return '';
		}
		$panes = [];
		foreach ( $projects as $project ) {
			$panes[] = [
				'title'       => $project->get_name(),
				'content'     => $this->get_project_properties( $project ),
				'show_button' => i18n::is_default_language(),
				'button_url'  => $project->get_permalink(),
				'button_text' => __( 'Bekijk project', 'siw' ),
			];

		}
		return Accordion::create()->add_items( $panes )->generate();
	}

	/**
	 * Haalt projecten op
	 * @return WC_Product_Project[]
	*/
	protected function get_projects(): array {
		$args = [
			'country'    => 'nederland',
		];
		return \siw_get_products( $args );
	}

	/** Genereert beschrijving van het project */
	protected function get_project_properties( WC_Product_Project $project ) : string {
		//Verzamelen gegevens
		$work_type_names = array_map(
			fn( Work_Type $work_type ) : string => $work_type->get_name(),
			$project->get_work_types()
		);
		
		$sdg_names = array_map(
			fn( Sustainable_Development_Goal $sdg ): string => $sdg->get_full_name(),
			$project->get_sustainable_development_goals()
		);

		$duration = siw_format_date_range( $project->get_start_date(), $project->get_end_date() );

		//Opbouwen beschrijving
		$description[] = sprintf( __( 'Projectcode: %s', 'siw' ), $project->get_sku() );
		$description[] = sprintf( __( 'Data: %s', 'siw' ), $duration );
		$description[] = sprintf( __( 'Soort werk: %s', 'siw' ), implode( ', ', $work_type_names ) );
		if ( ! empty( $sdg_names ) ) {
			$description[] = sprintf( __( 'Sustainable development goals: %s', 'siw' ), implode( ', ', $sdg_names ) ); //TODO: icons gebruiken
		}

	
		//Locatie tonen indien bekend TODO: Google Maps API gebruiken?
		if ( $project->get_meta( 'dutch_projects_city' ) && $project->get_meta( 'dutch_projects_province' ) ) {
			$description[] = sprintf(
				__( 'Locatie: %s, provincie %s', 'siw' ),
				$project->get_meta('dutch_projects_city'),
				siw_get_dutch_province( $project->get_meta( 'dutch_projects_province' ) )
			);
		}
		return wpautop( implode( BR, $description ) );
	}

	/** Haal knop naar Groepsproject op */
	protected function get_project_button( \WC_Product $project ): ?string {
		if ( ! i18n::is_default_language() ) {
			return null;
		}
		return Links::generate_button_link( $project->get_permalink(), __( 'Bekijk project', 'siw' ) );
	}
}
