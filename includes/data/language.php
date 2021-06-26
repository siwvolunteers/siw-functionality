<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een taal
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Language extends Data {

	/** Slug */
	const SLUG = 'slug';

	/** Plato-code */
	const PLATO_CODE = 'plato_code';

	/** Alle talen */
	const ALL = 'all';

	/** Talen voor vrijwilligers */
	const VOLUNTEER = 'volunteer';

	/** Projecttalen */
	const PROJECT = 'project';

	/** Slug */
	protected string $slug;
	
	/** Naam */
	protected string $name;

	/** PLATO-code */
	protected string $plato_code;

	/** Is dit een taal is die een vrijwilliger kan opgeven */
	protected bool $volunteer_language;

	/** Kan dit een projecttaal zijn */
	protected bool $project_language;

	/** Geeft slug van taal terug */
	public function get_slug() : string {
		return $this->slug;
	}

	/** Geeft naam van taal terug */
	public function get_name() : string {
		return $this->name;
	}

	/** Geeft PLATO-code van taal terug */
	public function get_plato_code() : string {
		return $this->plato_code;
	}

	/** Geeft terug of dit een taal is die een vrijwilliger kan opgeven */
	public function is_volunteer_language() : bool {
		return $this->volunteer_language;
	}

	/** Geeft terug of dit een projecttaal kan zijn */
	public function is_project_language() : bool {
		return $this->project_language;
	}

	/** Geeft aan of taal geldig is voor context */
	public function is_valid_for_context( string $context ) : bool {
		return (
			self::ALL == $context 
			|| ( self::VOLUNTEER == $context && $this->is_volunteer_language() )
			|| ( self::PROJECT == $context && $this->is_project_language() )
		);
	}
}
