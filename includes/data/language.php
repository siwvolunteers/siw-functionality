<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een taal
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Language extends Data {

	/** Slug */
	const SLUG = 'slug';

	/** Plato-code */
	const PLATO_CODE = 'plato_code';

	/** Slug */
	protected string $slug;

	/** Naam */
	protected string $name;

	/** PLATO-code */
	protected string $plato_code;

	/** Geeft slug van taal terug */
	public function get_slug() : string {
		return $this->slug;
	}

	/** Geeft naam van taal terug */
	public function get_name() : string {
		return $this->name;
	}

	/** Geeft PLATO-code van taal terug */
	public function get_plato_code() : string {
		return $this->plato_code;
	}

}
