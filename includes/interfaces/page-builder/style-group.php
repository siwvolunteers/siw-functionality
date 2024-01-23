<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

interface Style_Group extends Extension {

	public function add_style_group( array $groups, int|bool $post_id, array|bool $args ): array;
}
