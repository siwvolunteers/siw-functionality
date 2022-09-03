<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Interface voor PageBuilder extensie met style group
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Style_Group extends Extension {

	/** Voegt optiegroep toe */
	public function add_style_group( array $groups, int|bool $post_id, array|bool $args ) : array;

}
