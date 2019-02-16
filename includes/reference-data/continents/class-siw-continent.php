<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bevat informatie over een continent
 * 
 * @package 	SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */
class SIW_Continent {

	/**
	 * Slug van continent
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Naam van het continent
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Kleurcode van continent op kaart
	 *
	 * @var string
	 */
	protected $color;


	/**
	 * Constructor
	 */
	public function __construct( $continent ) {
		$defaults = [
			'slug'  => '',
			'name'  => '',
			'color' => ','
		];
		$continent = wp_parse_args( $continent, $defaults );
		$this->set_slug( $continent['slug'] );
		$this->set_name( $continent['name'] );
		$this->set_color( $continent['color'] );
	}

	/**
	 * Zet slug van continent
	 *
	 * @param string $slug
	 * @return void
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;
		return $this;
	}

	/**
	 * Geeft de slug van het continent terug
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Zet naam van continent
	 *
	 * @param string $name
	 * @return void
	 */
	public function set_name( $name ) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Geeft de naam van het continent terug
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Zet kleurcode van continent op kaart
	 *
	 * @param string $color
	 * @return void
	 */
	public function set_color( $color ) {
		$this->color = $color;
		return $this;
	}

	/**
	 * Geeft kleurcode van continent op kaart terug
	 *
	 * @return string
	 */
	public function get_color() {
		return $this->color;
	}
}
