<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een continent
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Continent {

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
	public function __construct( array $continent ) {
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
	public function get_slug() : string {
		return $this->slug;
	}

	/**
	 * Geeft de naam van het continent terug
	 *
	 * @return string
	 */
	public function get_name() : string {
		return $this->name;
	}

	/**
	 * Geeft kleurcode van continent op kaart terug
	 *
	 * @return string
	 */
	public function get_color() : string {
		return $this->color;
	}
}
