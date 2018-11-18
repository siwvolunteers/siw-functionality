<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Bevat informatie over een soort werk
 * 
 * @package 	SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
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
	 * Geeft aan of dit soort werk gekoppeld kan worden aan een Nederlands project
	 *
	 * @var boolean
	 */
	protected $for_dutch_projects;

	/**
	 * Geeft aan of dit soort werk gekoppeld kan worden aan een Op Maat project
	 *
	 * @var boolean
	 */
	protected $for_tailor_made_projects;

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
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		];
	 
		$data = wp_parse_args( $data, $defaults );

		$this->set_slug( $data[ 'slug' ] );
		$this->set_plato_code( $data[ 'plato_code' ] );
		$this->set_name( $data[ 'name' ] );
		$this->set_for_dutch_projects( $data[ 'dutch_projects' ] );
		$this->set_for_tailor_made_projects( $data[ 'tailor_made_projects' ] );

	}

	/**
	 * Zet de slug van het soort werk
	 *
	 * @param string $slug
	 * @return $self
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;
		return $this;
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
	 * Zet de naam van het soort werk
	 *
	 * @param string $name
	 * @return $self
	 */
	public function set_name( $name ) {
		$this->name = $name;
		return $this;
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
	 * Zet de Plato-code van het soort werk
	 *
	 * @param string $plato_code
	 * @return $self
	 */
	public function set_plato_code( $plato_code ) {
		$this->plato_code = $plato_code;
		return $this;
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
	 * Zet of dit soort werk gekoppeld kan worden aan een Nederlands project
	 *
	 * @param bool $value
	 * @return $self
	 */
	public function set_for_dutch_projects( $value ) {
		$this->for_dutch_projects = $value;
		return $this;
	}

	/**
	 * Geeft terug of dit soort werk gekoppeld kan worden aan een Nederlands project
	 *
	 * @return boolean
	 */
	public function is_for_dutch_projects() {
		return $this->for_dutch_projects;
	} 

	/**
	 * Zet of dit soort werk gekoppeld kan worden aan een Op Maat project
	 *
	 * @param bool $value
	 * @return $self
	 */
	public function set_for_tailor_made_projects( $value ) {
		$this->for_tailor_made_projects = $value;
		return $this;
	}

	/**
	 * Geeft terug of dit soort werk gekoppeld kan worden aan een Op Maat project
	 *
	 * @return boolean
	 */
	public function is_for_tailor_made_projects() {
		return $this->for_tailor_made_projects;
	}
}