<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;


interface Style_CSS extends Extension {
	public function set_style_css( array $style_css, array $style_args ): array;
}
