<?php declare(strict_types=1);

namespace SIW\Elements\Interactive_Maps;

use SIW\Data\Sustainable_Development_Goal;
use SIW\Data\Work_Type;
use SIW\Interfaces\Elements\Interactive_Map as Interactive_Map_Interface;

use SIW\Elements\Accordion_Tabs;
use SIW\I18n;
use SIW\Util\CSS;
use SIW\Util\Links;
use SIW\WooCommerce\Product\WC_Product_Project;

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
			'searchfields' => [ 'title', 'about', 'description' ],
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
				'id'          => sanitize_title( $project->get_sku() ),
				'title'       => $project->get_name(),
				'image'       => $project->get_image_id() ? wp_get_attachment_image_src( $project->get_image_id(), 'medium' )[0] : null,
				'about'       => $project->get_sku(),
				'lat'         => $project->get_latitude() ?? null,
				'lng'         => $project->get_longitude() ?? null,
				'description' => $this->get_project_properties( $project ) . $this->get_project_button( $project ),
				'pin'         => 'pin-classic pin-md',
				'category'    => 'nl',
				'fill'        => CSS::ACCENT_COLOR,
			];
			$provinces[] = null; // TODO: provincie uit Google Maps halen
		}

		// Provincies inkleuren
		$provinces = array_unique( $provinces );
		foreach ( $provinces as $province ) {
			$locations[] = [
				'id'     => "nl-{$province}",
				'fill'   => CSS::CONTRAST_COLOR,
				'action' => 'disabled',
				'hide'   => true,
			];
		}

		return $locations;
	}

	/** {@inheritDoc} */
	public function get_mobile_content(): string {

		$projects = $this->get_projects();
		if ( empty( $projects ) ) {
			return '';
		}
		$panes = [];
		foreach ( $projects as $project ) {
			$panes[] = [
				'title'       => $project->get_name(),
				'content'     => $this->get_project_properties( $project ),
				'show_button' => I18n::is_default_language(),
				'button_url'  => $project->get_permalink(),
				'button_text' => __( 'Bekijk project', 'siw' ),
			];

		}
		return Accordion_Tabs::create()->add_items( $panes )->generate();
	}

	/**
	 * Haalt projecten op
	 *
	 * @return WC_Product_Project[]
	 */
	protected function get_projects(): array {
		$args = [
			'country' => 'nederland',
		];
		$projects = siw_get_products( $args );
		$projects = array_filter(
			siw_get_products( $args ),
			fn( WC_Product_Project $project ): bool => ! $project->is_hidden()
		);
		usort( $projects, fn( WC_Product_Project $project_1, WC_Product_Project $project_2 ) => strcmp( $project_1->get_sku(), $project_2->get_sku() ) );
		return $projects;
	}

	/** Genereert beschrijving van het project */
	protected function get_project_properties( WC_Product_Project $project ): string {
		// Verzamelen gegevens
		$duration = siw_format_date_range( $project->get_start_date(), $project->get_end_date() );
		$work_types = array_map(
			fn( Work_Type $work_type ): string => $work_type->get_name(),
			$project->get_work_types()
		);

		$sdgs = array_map(
			fn( Sustainable_Development_Goal $sdg ): string => $sdg->get_full_name(),
			$project->get_sustainable_development_goals()
		);

		// Opbouwen beschrijving
		$description[] = sprintf( __( 'Projectcode: %s', 'siw' ), $project->get_sku() ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
		$description[] = sprintf( __( 'Data: %s', 'siw' ), $duration ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
		$description[] = sprintf( __( 'Soort werk: %s', 'siw' ), implode( ', ', $work_types ) ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment

		if ( ! empty( $sdgs ) ) {
			$description[] = sprintf( __( 'Sustainable Development Goals: %s', 'siw' ), implode( ', ', $sdgs ) ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
			// TODO: icons gebruiken?
		}

		// TODO: Locatie (Locatie: %s, provincie %s) tonen indien bekend, afleiden van coördinaten m.b.v Google Maps API
		return wpautop( implode( BR, $description ) );
	}

	/** Haal knop naar Groepsproject op */
	protected function get_project_button( WC_Product_Project $project ): ?string {
		if ( ! I18n::is_default_language() ) {
			return null;
		}
		return Links::generate_button_link( $project->get_permalink(), __( 'Bekijk project', 'siw' ) );
	}
}
