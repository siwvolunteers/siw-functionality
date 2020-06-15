<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een Sustainable Development Goal
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.0
 */
class Sustainable_Development_Goal {

	/**
	 * Slug
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Nummer
	 *
	 * @var int
	 */
	protected $number;

	/**
	 * Naam
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Kleurcode
	 *
	 * @var string
	 */
	protected $color;

	/**
	 * CSS-class van icoon
	 *
	 * @var string
	 */
	protected $icon_class;

	/**
	 * Constructor
	 *
	 * @param array $sdg
	 */
	public function __construct( array $sdg ) {
		$defaults = [
			'slug'               => '',
			'number'             => 0,
			'name'               => '',
			'icon_class'         => '',
			'color'              => '',
		];
		$sdg = wp_parse_args( $sdg, $defaults );

		$this->slug = $sdg['slug'];
		$this->number = $sdg['number'];
 		$this->name = $sdg['name'];
		$this->icon_class = $sdg['icon_class'];
		$this->color = $sdg['color'];
	}

	/**
	 * Geeft slug van sdg terug
	 * 
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Geeft de naam van het sdg terug
	 * 
	 * @return int
	 */
	public function get_number() : int {
		return $this->number;
	}

	/**
	 * Geeft de naam van het sdg terug
	 * 
	 * @return string
	 */
	public function get_name() : string {
		return $this->name;
	}

	/**
	 * Geeft volledige naam (nummer + naam) terug
	 *
	 * @return string
	 */
	public function get_full_name() : string {
		return sprintf( '%d. %s', $this->number, $this->name );
	}

	/**
	 * Geeft icon class voor voor sdg terug
	 * 
	 * @return string
	 */
	public function get_icon_class() : string {
		return $this->icon_class;
	}

	/**
	 * Geeft kleurcode van sdg terug
	 * 
	 * @return string
	 */
	public function get_color() : string {
		return $this->color;
	}

}
