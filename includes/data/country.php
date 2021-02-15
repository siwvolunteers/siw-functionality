<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Data\Continent;

/**
 * Bevat informatie over een land
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Country {

	/** ISO-code van het land */
	protected string $iso_code;

	/** Naam van het land */
	protected string $name;

	/** Slug van het land */
	protected string $slug;

	/** Continent van het land */
	protected string $continent;

	/** Geeft aan of het land toegestaan is */
	protected bool $allowed;

	/** Geeft aan of het land groepsprojecten heeft */
	protected bool $has_workcamps;

	/** Geeft aan of het land ESC-projecten heeft */
	protected bool $has_esc_projects;

	/** Geeft aan of het land Op Maat projecten heeft */
	protected bool $has_tailor_made_projects;

	/** Eigenschappen van land voor kaart van de wereld */
	protected \stdClass $world_map_data;

	/** Eigenschappen van land voor kaart van Europa */
	protected \stdClass $europe_map_data;

	/** Constructor */
	public function __construct( array $country ) {

		$defaults = [
			'iso'         => '',
			'slug'        => '',
			'name'        => '',
			'continent'   => '',
			'allowed'     => true,
			'workcamps'   => false,
			'tailor_made' => false,
			'esc'         => false,
			'world_map'   => [],
			'europe_map'  => [],
		];
		$country = wp_parse_args( $country, $defaults );

		$this->iso_code = $country['iso'];
		$this->slug = $country['slug'];
		$this->name = $country['name'];
		$this->continent = $country['continent'];
		$this->allowed = $country['allowed'];
		$this->has_workcamps = $country['workcamps'];
		$this->has_tailor_made_projects = $country['tailor_made'];
		$this->has_esc_projects = $country['esc'];
		$this->world_map_data = (object) $country['world_map'];
		$this->europe_map_data = (object) $country['europe_map'];
	}

	/** Geeft ISO-code van het land terug */
	public function get_iso_code() : string {
		return $this->iso_code;
	}

	/** Geeft slug van het land terug */
	public function get_slug() : string {
		return $this->slug;
	}

	/** Geeft naam van land terug */
	public function get_name() : string {
		return $this->name;
	}

	/** Geeft continent van land terug */
	public function get_continent() : Continent {
		return siw_get_continent( $this->continent );
	}

	/** Geeft aan of land toegestaan is */
	public function is_allowed() : bool {
		return $this->allowed;
	}

	/** Geeft aan of het land groepsprojecten heeft */
	public function has_workcamps() : bool {
		return $this->has_workcamps;
	}

	/** Geeft aan of het land ESC-projecten heeft */
	public function has_esc_projects() : bool {
		return $this->has_esc_projects;
	}

	/** Geeft aan of het land Op Maat projecten heeft */
	public function has_tailor_made_projects() : bool {
		return $this->has_tailor_made_projects;
	}

	/** Geeft de gegevens van het land voor de kaart van de wereld terug */
	public function get_world_map_data() : \stdClass {
		return $this->world_map_data;
	}
	
	/** Geeft de gegevens van het land voor de kaart van Europa terug */
	public function get_europe_map_data() : \stdClass {
		return $this->europe_map_data;
	}
}
