<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Bevat informatie over een taal
 * 
 * @package   SIW\Reference-Data
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
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
	protected $volunteer_language;

	/**
	 * Geeft aan of dit een projecttaal kan zijn
	 *
	 * @var bool
	 */
	protected $project_language;

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
		$this->slug = $language['slug'];
		$this->name = $language['name'];
		$this->plato_code = $language[ 'plato'];
		$this->volunteer_language = $language['volunteer_language']; 
		$this->project_language = $language['project_language']; 
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
	 * @return string
	 */
	public function get_plato_code() {
		return $this->plato_code;
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
	 * Geeft terug of dit een projecttaal kan zijn
	 *
	 * @return boolean
	 */
	public function is_project_language() {
		return $this->project_language;
	}
}