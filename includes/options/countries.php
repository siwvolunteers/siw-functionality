<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Data\Continent;
use SIW\Interfaces\Options\Option as Option_Interface;

/**
 * Opties voor landen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
class Countries implements Option_Interface {

	/**
	 * {@inheritDoc}
	 */
	public function get_id(): string {
		return 'countries';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_parent_page(): string {
		return 'options-general.php';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_capability(): string {
		return 'manage_options';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_title(): string {
		return __( 'Landen', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_tabs() : array {
		return array_map(
			fn( Continent $continent ) : array => [
				'id'    => $continent->get_slug(),
				'label' => $continent->get_name(),
			],
			\siw_get_continents()
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_fields() : array {

		$countries = \siw_get_countries();

		//TODO: siw_get_project_types gebruiken?
		$available_projects = [
			'workcamps'   => __( 'Groepsprojecten', 'siw' ),
			'tailor_made' => __( 'Projecten op maat', 'siw' ),
			'esc'         => __( 'ESC', 'siw' ),
		];

		foreach ( $countries as $country ) {
			$fields[] = [
				'id'            => $country->get_slug(),
				'type'          => 'group',
				'tab'           => $country->get_continent()->get_slug(),
				'group_title'   => $country->get_name(),
				'collapsible'   => true,
				'default_state' => 'collapsed',
				'fields'        => [
					[
						'id'      => 'available_projects',
						'type'    => 'checkbox_list',
						'name'    => __( 'Aanbod', 'siw' ),
						'options' => $available_projects,
						'std'     => [
							$country->has_workcamps() ? 'workcamps' : '',
							$country->has_tailor_made_projects() ? 'tailor_made' : '',
							$country->has_esc_projects() ? 'esc' : '',
						],
						'disabled' => true,
						'readonly' => true,
					],
				],
			];
		}
		return $fields;
	}

}