<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een soort werk
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Work_Type {
	
	/**
	 * De slug van het soort werk
	 */
	protected string $slug;

	/**
	 * Naam van het soort werk
	 */
	protected string $name;

	/**
	 * De Plato-code van het soort werk
	 */
	protected string $plato_code;
	
	/**
	 * CSS-class van icoon
	 */
	protected string $icon_class;

	/**
	 * Geeft aan of dit soort werk gekoppeld kan worden aan een Nederlands project
	 */
	protected bool $dutch_projects;

	/**
	 * Geeft aan of dit soort werk gekoppeld kan worden aan een Op Maat project
	 */
	protected bool $tailor_made_projects;

	/**
	 * Constructor
	 *
	 * @param array $data
	 */
	public function __construct( array $data ) {

		$defaults = [
			'slug'                  => '',
			'plato_code'            => '',
			'name'                  => '',
			'icon_class'            => '', 
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		];
	 
		$data = wp_parse_args( $data, $defaults );

		$this->slug = $data[ 'slug' ];
		$this->plato_code = $data[ 'plato_code' ];
		$this->name = $data[ 'name' ];
		$this->dutch_projects = $data[ 'dutch_projects' ];
		$this->tailor_made_projects = $data[ 'tailor_made_projects' ];
		$this->icon_class = $data['icon_class'];
	}

	/**
	 * Geeft de slug van het soort werk terug
	 *
	 * @return string
	 */
	public function get_slug() : string {
		return $this->slug;
	}

	/**
	 * Geeft de naam van het soort werk terug
	 *
	 * @return string
	 */
	public function get_name() : string {
		return $this->name;
	}

	/**
	 * Geeft de Plato-code van het soort werk terug
	 *
	 * @return string
	 */
	public function get_plato_code() : string {
		return $this->plato_code;
	}

	/**
	 * Geeft icon class voor voor soort -werk terug
	 * 
	 * @return string
	 */
	public function get_icon_class() : string {
		return $this->icon_class;
	}

	/**
	 * Geeft terug of dit soort werk gekoppeld kan worden aan een Nederlands project
	 *
	 * @return bool
	 */
	public function is_for_dutch_projects() : bool {
		return $this->dutch_projects;
	}

	/**
	 * Geeft terug of dit soort werk gekoppeld kan worden aan een Op Maat project
	 *
	 * @return bool
	 */
	public function is_for_tailor_made_projects() : bool {
		return $this->tailor_made_projects;
	}
}
