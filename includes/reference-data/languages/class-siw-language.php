<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Bevat informatie over een taal
 * 
 * @package 	SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */
class SIW_Language {

	/**
	 * @var string
	 */
	protected $slug;
	
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $plato_code;

	/**
	 * Geeft dit een taal is die een vrijwilliger kan opgeven
	 *
	 * @var bool
	 */
	protected $is_volunteer_language;

	/**
	 * Geeft aan of dit een projecttaal kan zijn
	 *
	 * @var bool
	 */
	protected $is_project_language;

	/**
	 * @param array $language
	 */
	public function __construct( $language ) {
		$defaults = [
			'slug'               => '',
			'name'               => '',
			'plato'              => ',',
			'volunteer_language' => false,
			'project_language'   => false,
		];
		$language = wp_parse_args( $language, $defaults );
		$this->set_slug( $language['slug'] );
		$this->set_name( $language['name'] );
		$this->set_plato_code( $language[ 'plato'] );
		$this->set_volunteer_language( $language['volunteer_language'] ); 
		$this->set_project_language( $language['project_language'] ); 
	}

	/**
	 * @param string $slug
	 * @return $this
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;
		return $this;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function set_name( $name ) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @param string $plato_code
	 * @return $this
	 */
	public function set_plato_code( $plato_code ) {
		$this->plato_code = $plato_code;
		return $this;
	}

	/**
	 * @return string
	 */
	public function get_plato_code() {
		return $this->plato_code;
	}

	/**
	 * Zet of dit een taal is die een vrijwilliger kan opgeven
	 *
	 * @param bool $value
	 * @return $this
	 */
	public function set_volunteer_language( $value ) {
		$this->volunteer_language = $value;
		return $this;
	}

	/**
	 * Geeft terug of dit een taal is die een vrijwilliger kan opgeven
	 *
	 * @return boolean
	 */
	public function is_volunteer_language() {
		return $this->volunteer_language;
	}

	/**
	 * Zet of dit een projecttaal kan zijn
	 *
	 * @param bool $value
	 * @return $this
	 */
	public function set_project_language( $value ) {
		$this->project_language = $value;
		return $this;
	}

	/**
	 * Geeft terug of dit een projecttaal kan zijn
	 *
	 * @return boolean
	 */
	public function is_project_language() {
		return $this->project_language;
	}
}