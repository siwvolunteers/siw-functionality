<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Interface voor PageBuilder extensie met style fields
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Style_Fields extends Extension {

	/** Voegt velden toe */
	public function add_style_fields( array $fields, int|bool $post_id, array|bool $args ) : array;
}
