<?php declare(strict_types=1);

namespace SIW\Options;

/**
 * Opties voor landen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
class Countries extends Option {

	/**
	 * {@inheritDoc}
	 */
	protected string $id = 'countries';

	/**
	 * {@inheritDoc}
	 */
	protected function get_title(): string {
		return __( 'Landen', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_tabs() : array {
		$continents = \siw_get_continents();

		$tabs = [];
		foreach ( $continents as $continent ) {
			$tabs[] = [
				'id'    => $continent->get_slug(),
				'label' => $continent->get_name(),
			];
		}
		return $tabs;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_fields() : array {

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