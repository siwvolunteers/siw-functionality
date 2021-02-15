<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een soort werk
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Work_Type extends Data{
	
	/** De slug van het soort werk */
	protected string $slug;

	/** Naam van het soort werk */
	protected string $name;

	/** De Plato-code van het soort werk */
	protected string $plato_code;
	
	/** CSS-class van icoon */
	protected string $icon_class;

	/** Geeft aan of dit soort werk gekoppeld kan worden aan een Op Maat project */
	protected bool $tailor_made_projects;

	/** Geeft de slug van het soort werk terug */
	public function get_slug() : string {
		return $this->slug;
	}

	/** Geeft de naam van het soort werk terug */
	public function get_name() : string {
		return $this->name;
	}

	/** Geeft de Plato-code van het soort werk terug */
	public function get_plato_code() : string {
		return $this->plato_code;
	}

	/** Geeft icon class voor voor soort -werk terug */
	public function get_icon_class() : string {
		return $this->icon_class;
	}

	/** Geeft terug of dit soort werk gekoppeld kan worden aan een Op Maat project */
	public function is_for_tailor_made_projects() : bool {
		return $this->tailor_made_projects;
	}
}
