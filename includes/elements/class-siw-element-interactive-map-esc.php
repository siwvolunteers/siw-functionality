<?php

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @package   SIW\Elements
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * */
class SIW_Element_Interactive_Map_ESC extends SIW_Element_Interactive_Map {

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
	protected function get_locations() {
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
	protected function get_mobile_content() {
		return null;
	}


}