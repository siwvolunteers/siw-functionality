<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

interface Style_Attributes extends Extension {
	public function set_style_attributes( array $style_attributes, array $style_args ): array;
}
