<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een Sustainable Development Goal
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.0
 */
class Sustainable_Development_Goal {

	/** Slug */
	protected string $slug;

	/** Nummer */
	protected int $number;

	/** Naam */
	protected string $name;

	/** Kleurcode */
	protected string $color;

	/** CSS-class van icoon */
	protected string $icon_class;

	/**
	 * Constructor

	 */
	public function __construct( array $data ) {
		$defaults = [
			'slug'               => '',
			'number'             => 0,
			'name'               => '',
			'icon_class'         => '',
			'color'              => '',
		];
		$data = wp_parse_args( $data, $defaults );
		$data = wp_array_slice_assoc( $data, array_keys( $defaults ) );
		
		foreach( $data as $key => $value ) {
			$this->$key = $value;
		}
	}

	/** Geeft slug van sdg terug */
	public function get_slug() : string {
		return $this->slug;
	}

	/** Geeft de naam van het sdg terug */
	public function get_number() : int {
		return $this->number;
	}

	/** Geeft de naam van het sdg terug */
	public function get_name() : string {
		return $this->name;
	}

	/** Geeft volledige naam (nummer + naam) terug */
	public function get_full_name() : string {
		return sprintf( '%d. %s', $this->number, $this->name );
	}

	/** Geeft icon class voor voor sdg terug */
	public function get_icon_class() : string {
		return $this->icon_class;
	}

	/** Geeft kleurcode van sdg terug */
	public function get_color() : string {
		return $this->color;
	}
}
