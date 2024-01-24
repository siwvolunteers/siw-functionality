<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

interface Style_Fields extends Extension {
	public function add_style_fields( array $fields, int|bool $post_id, array|bool $args ): array;
}
