<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bevat informatie over een continent
 * 
 * @package   SIW\Reference-Data
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
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
			'color' => '',
		];
		$continent = wp_parse_args( $continent, $defaults );
		$this->slug = $continent['slug'];
		$this->name = $continent['name'];
		$this->color = $continent['color'];
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
	 * Geeft de naam van het continent terug
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
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