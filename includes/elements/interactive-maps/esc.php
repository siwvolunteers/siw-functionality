<?php declare(strict_types=1);

namespace SIW\Elements\Interactive_Maps;

use SIW\Elements\Interactive_Map;

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class ESC extends Interactive_Map {

	/**
	 * {@inheritDoc}
	 */
	protected $id = 'esc';

	/**
	 * {@inheritDoc}
	 */
	protected $file = 'europe';
	
	/**
	 * {@inheritDoc}
	 */
	protected $data = [
		'mapwidth'  => 600,
		'mapheight' => 600,
	];

	/**
	 * {@inheritDoc}
	 */
	protected $options = [
		'search' => true,
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
	protected function get_locations() : array {
		$countries = siw_get_countries( 'esc_projects' );
		$locations = [];
		foreach ( $countries as $country ) {
			$europe_map_data = $country->get_europe_map_data();
			$locations[] = [
				'id'        => $europe_map_data->code,
				'title'     => $country->get_name(),
				'x'         => $europe_map_data->x,
				'y'         => $europe_map_data->y,
				'category'  => 'bestemmingen'
			];
		}
		return $locations;
	}

	/**
	 * {@inheritDoc}
	 * 
	 * @todo tabel met ESC-landen o.i.d.
	 */
	protected function get_mobile_content() : ?string {
	}
}
