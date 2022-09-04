<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * TODO:
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Archive_Columns {

	/** Geeft aantal kolommen terug */
	public function get_archive_column_count(): int;

	/** Geeft aan of post type masonry gebruikt */
	public function get_use_masonry(): bool;
}
