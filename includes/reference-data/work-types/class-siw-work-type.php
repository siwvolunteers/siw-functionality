<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Bevat informatie over een soort werk
 * 
 * @package   SIW\Reference-Data
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_Work_Type {
	
	/**
	 * De slug van het soort werk
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Naam van het soort werk
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * De Plato-code van het soort werk
	 *
	 * @var string
	 */
	protected $plato_code;
	
	/**
	 * CSS-class van icoon
	 *
	 * @var string
	 */
	protected $icon_class;

	/**
	 * Geeft aan of dit soort werk gekoppeld kan worden aan een Nederlands project
	 *
	 * @var boolean
	 */
	protected $dutch_projects;

	/**
	 * Geeft aan of dit soort werk gekoppeld kan worden aan een Op Maat project
	 *
	 * @var boolean
	 */
	protected $tailor_made_projects;

	/**
	 * Constructor
	 *
	 * @param array $data
	 */
	public function __construct( $data ) {

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
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Geeft de naam van het soort werk terug
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Geeft de Plato-code van het soort werk terug
	 *
	 * @return string
	 */
	public function get_plato_code() {
		return $this->plato_code;
	}

	/**
	 * Geeft icon class voor voor soort -werk terug
	 * 
	 * @return string
	 */
	public function get_icon_class() {
		return $this->icon_class;
	}

	/**
	 * Geeft terug of dit soort werk gekoppeld kan worden aan een Nederlands project
	 *
	 * @return boolean
	 */
	public function is_for_dutch_projects() {
		return $this->dutch_projects;
	}

	/**
	 * Geeft terug of dit soort werk gekoppeld kan worden aan een Op Maat project
	 *
	 * @return boolean
	 */
	public function is_for_tailor_made_projects() {
		return $this->tailor_made_projects;
	}
}