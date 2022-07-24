<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Generieke interface voor PageBuilder extensie met style fields en group
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Style_Group extends Style_Fields {

	/** Voegt optiegroep toe */
	public function add_style_group( array $groups, int|bool $post_id, array|bool $args ) : array;

}
