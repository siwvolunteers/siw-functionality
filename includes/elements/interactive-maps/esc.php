<?php declare(strict_types=1);

namespace SIW\Elements\Interactive_Maps;

use SIW\Interfaces\Elements\Interactive_Map as Interactive_Map_Interface;

use SIW\Data\Country;
use SIW\Elements\List_Columns;

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class ESC implements Interactive_Map_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'esc';
	}

	/** {@inheritDoc} */
	public function get_file(): string {
		return 'europe';
	}

	/** {@inheritDoc} */
	public function get_options(): array {
		return [
			'search' => true,
		];
	}

	/** {@inheritDoc} */
	public function get_map_data(): array {
		return [
			'mapwidth'  => 600,
			'mapheight' => 600,
		];
	}
	
	/** {@inheritDoc} */
	public function get_categories() : array {
		return [];
	}

	/** {@inheritDoc} */
	public function get_locations() : array {
		$countries = siw_get_countries( Country::ESC );
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

	/** {@inheritDoc} */
	public function get_mobile_content() : string {
		$countries = siw_get_countries_list( Country::ESC );
		return List_Columns::create()->add_items( array_values( $countries ) )->set_columns( 2 )->generate();
	}
}
