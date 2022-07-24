<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Generieke interface voor PageBuilder extensie met style fields
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Style_Fields {

	/** Voegt velden toe */
	public function add_style_fields( array $fields, int|bool $post_id, array|bool $args ) : array;

	/** Zet attributes */
	public function set_style_attributes( array $style_attributes, array $style_args ) : array;

	/** Geeft aan of deze extensie widgets ondersteunt */
	public function supports_widgets(): bool;

	/** Geeft aan of deze extensie cells ondersteunt */
	public function supports_cells(): bool;

	/** Geeft aan of deze extensie rows ondersteunt */
	public function supports_rows(): bool;

}
