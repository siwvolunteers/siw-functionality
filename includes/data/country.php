<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Data\Continent;

/**
 * Bevat informatie over een land
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Country extends Data {

	/** Slug */
	const SLUG = 'slug';

	/** ISO-code */
	const ISO_CODE = 'iso_code';

	/** Plato-code */
	const PLATO_CODE = 'plato_code';

	/** ISO-code van het land */
	protected string $iso_code;

	/** Plato code van het land */
	protected string $plato_code;

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
	protected array $world_map = [];

	/** Geeft Plato-code van het land terug */
	public function get_plato_code(): string {
		return $this->plato_code;
	}

	/** Geeft ISO-code van het land terug */
	public function get_iso_code(): string {
		return $this->iso_code;
	}

	/** Geeft slug van het land terug */
	public function get_slug(): string {
		return $this->slug;
	}

	/** Geeft naam van land terug */
	public function get_name(): string {
		return $this->name;
	}

	/** Geeft continent van land terug */
	public function get_continent(): Continent {
		return siw_get_continent( $this->continent );
	}

	/** Geeft aan of het land projecten heeft */
	public function has_projects(): bool {
		return $this->has_workcamps() || $this->has_tailor_made_projects() || $this->has_esc_projects();
	}

	/** Geeft aan of het land groepsprojecten heeft */
	public function has_workcamps(): bool {
		return $this->workcamps;
	}

	/** Geeft aan of het land ESC-projecten heeft */
	public function has_esc_projects(): bool {
		return $this->esc;
	}

	/** Geeft aan of het land Op Maat projecten heeft */
	public function has_tailor_made_projects(): bool {
		return $this->tailor_made;
	}

	/** Geeft de coördinaten van het land voor de kaart van de wereld terug indien het niet (goed) op de kaart staat. */
	public function get_world_map_coordinates(): \stdClass {
		return (object) $this->world_map;
	}

	/** Geeft aan of land geldig is voor context */
	public function is_valid_for_context( Country_Context $context ): bool {

		switch ( $context ) {
			case Country_Context::ALL:
				return true;
			case Country_Context::WORKCAMPS:
				return $this->has_workcamps();
			case Country_Context::ESC:
				return $this->has_esc_projects();
			case Country_Context::TAILOR_MADE:
				return $this->has_tailor_made_projects();
			case Country_Context::PROJECTS:
				return $this->has_projects();
			case Country_Context::AFRICA:
			case Country_Context::ASIA:
			case Country_Context::EUROPE:
			case Country_Context::NORTH_AMERICA:
			case Country_Context::LATIN_AMERICA:
				return $context->value === $this->get_continent()->get_slug();
			default:
				return false;
		}
	}
}
