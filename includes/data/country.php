<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Data\Continent;

/**
 * Bevat informatie over een land
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Country extends Data {

	/** Alle landen */
	const ALL = 'all';
	
	/** Landen met groepsprojecten */
	const WORKCAMPS = 'workcamps';

	/** Landen met ESC-projecten */
	const ESC = 'esc_projects';

	/** Landen met projecten op maat */
	const TAILOR_MADE = 'tailor_made_projects';

	/** Toegestane landen */
	const ALLOWED = 'allowed';

	/** Landen in Afrika */
	const AFRICA = 'afrika';

	/** Landen in Azië */
	const ASIA = 'azie';

	/** Landen in Europa */
	const EUROPE = 'europa';

	/** Landen in Noord-Amerika */
	const NORTH_AMERICA = 'noord_amerika';

	/** Landen in Latijns-Amerika */
	const LATIN_AMERICA = 'latijns_amerika';

	/** ISO-code van het land */
	protected string $iso_code;

	/** Naam van het land */
	protected string $name;

	/** Slug van het land */
	protected string $slug;

	/** Continent van het land */
	protected string $continent;

	/** Geeft aan of het land toegestaan is */
	protected bool $allowed = false;

	/** Geeft aan of het land groepsprojecten heeft */
	protected bool $workcamps = false;

	/** Geeft aan of het land ESC-projecten heeft */
	protected bool $esc = false;

	/** Geeft aan of het land Op Maat projecten heeft */
	protected bool $tailor_made = false;

	/** Eigenschappen van land voor kaart van de wereld */
	protected array $world_map;

	/** Eigenschappen van land voor kaart van Europa */
	protected array $europe_map;

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
		return $this->workcamps;
	}

	/** Geeft aan of het land ESC-projecten heeft */
	public function has_esc_projects() : bool {
		return $this->esc;
	}

	/** Geeft aan of het land Op Maat projecten heeft */
	public function has_tailor_made_projects() : bool {
		return $this->tailor_made;
	}

	/** Geeft de gegevens van het land voor de kaart van de wereld terug */
	public function get_world_map_data() : \stdClass {
		return (object) $this->world_map;
	}
	
	/** Geeft de gegevens van het land voor de kaart van Europa terug */
	public function get_europe_map_data() : \stdClass {
		return (object) $this->europe_map;
	}

	/** Geeft aan of land geldig is voor context */
	public function is_valid_for_context( string $context ) : bool {
		return (
			self::ALL == $context
			|| ( self::WORKCAMPS == $context && $this->has_workcamps() )
			|| ( self::ESC == $context && $this->has_esc_projects() )
			|| ( self::TAILOR_MADE == $context && $this->has_tailor_made_projects() )
			|| ( self::ALLOWED == $context && $this->is_allowed() )
			|| ( self::AFRICA == $context && $context == $this->get_continent()->get_slug() )
			|| ( self::ASIA == $context && $context == $this->get_continent()->get_slug() )
			|| ( self::EUROPE == $context && $context == $this->get_continent()->get_slug() )
			|| ( self::NORTH_AMERICA == $context && $context == $this->get_continent()->get_slug() )
			|| ( self::LATIN_AMERICA == $context && $context == $this->get_continent()->get_slug() )
		);
	}
}
