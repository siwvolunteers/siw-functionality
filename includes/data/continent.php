<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een continent
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Continent extends Data {

	/** Slug van continent */
	protected string $slug;

	/** Naam van het continent */
	protected string $name;

	/** Kleurcode van continent op kaart */
	protected string $color;

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
