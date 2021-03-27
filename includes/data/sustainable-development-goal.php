<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een Sustainable Development Goal
 * 
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 */
class Sustainable_Development_Goal extends Data {

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
