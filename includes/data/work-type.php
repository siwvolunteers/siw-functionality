<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een soort werk
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Work_Type extends Data{
	
	/** Alle soorten werk */
	const ALL = 'all';

	/** Soorten werk voor Op Maat projecten*/
	const TAILOR_MADE = 'tailor_made_projects';

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

	/** Geeft aan of soort werk geldig is voor context */
	public function is_valid_for_context( string $context ) : bool {
		return (
			self::ALL == $context
			|| ( self::TAILOR_MADE == $context && $this->is_for_tailor_made_projects() )
		);
	}
}
