<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een continent
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Continent {

	/** Slug van continent */
	protected string $slug;

	/** Naam van het continent */
	protected string $name;

	/** Kleurcode van continent op kaart */
	protected string $color;

	/** Constructor */
	public function __construct( array $data ) {
		$defaults = [
			'slug'  => '',
			'name'  => '',
			'color' => '',
		];
		$data = wp_parse_args( $data, $defaults );
		$data = wp_array_slice_assoc( $data, array_keys( $defaults ) );
		
		foreach( $data as $key => $value ) {
			$this->$key = $value;
		}
	}

	/** Geeft de slug van het continent terug */
	public function get_slug() : string {
		return $this->slug;
	}

	/** Geeft de naam van het continent terug */
	public function get_name() : string {
		return $this->name;
	}

	/** Geeft kleurcode van continent op kaart terug */
	public function get_color() : string {
		return $this->color;
	}
}
